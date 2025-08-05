<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

use \RuntimeException;
use \Traversable;

/**
 * Built-in HTTP router class,
 * @see https://dannyvankooten.github.io/AltoRouter/
 */
class Router
{
    /**
     * @access protected
     * @var string REGEX
     */
    public const REGEX = '`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`';

    /**
     * @access protected
     * @var array $routes
     * @var array $namedRoutes
     * @var array $basePath
     * @var array $matchTypes
     */
    protected $routes = [];
    protected $namedRoutes = [];
    protected $basePath = '';
    protected $matchTypes = [
        'i'  => '[0-9]++',
        'a'  => '[0-9A-Za-z]++',
        'h'  => '[0-9A-Fa-f]++',
        '*'  => '.+?',
        '**' => '.++',
        ''   => '[^/\.]++'
    ];

    /**
     * @inheritdoc
     */
    public function __construct(array $routes = [], string $basePath = '', array $matchTypes = [])
    {
        $this->addRoutes($routes);
        $this->setBasePath($basePath);
        $this->addMatchTypes($matchTypes);
    }

    /**
     * @inheritdoc
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @inheritdoc
     */
    public function addRoutes($routes)
    {
        if (!TypeCheck::isArray($routes) && !($routes instanceof Traversable)) {
            throw new RuntimeException('Routes should be an array or an instance of Traversable');
        }
        foreach ($routes as $route) {
            call_user_func_array([$this, 'map'], $route);
        }
    }

    /**
     * @inheritdoc
     */
    public function setBasePath(string $basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @inheritdoc
     */
    public function addMatchTypes(array $matchTypes)
    {
        $this->matchTypes = Arrayify::merge($this->matchTypes, $matchTypes);
    }

    /**
     * @inheritdoc
     */
    public function map(string $method, string $route, $controller, ?string $name = null, $permission = null)
    {
        $this->routes[] = [$method, $route, $controller, $name, $permission];
        if ($name) {
            if (isset($this->namedRoutes[$name])) {
                throw new RuntimeException("Can not redeclare route '{$name}'");
            }
            $this->namedRoutes[$name] = $route;
        }
        return;
    }

    /**
     * @inheritdoc
     */
    public function generate(string $routeName, array $params = []): string
    {
        // Check if named route exists
        if (!isset($this->namedRoutes[$routeName])) {
            throw new RuntimeException("Route '{$routeName}' does not exist");
        }

        // Replace named parameters
        $route = $this->namedRoutes[$routeName];

        // prepend base path to route url again
        $url = "{$this->basePath}{$route}";
        if ($matches = Stringify::matchAll(static::REGEX, $route, -1, PREG_SET_ORDER)) {
            foreach ($matches as $index => $match) {

                list($block, $pre, $type, $param, $optional) = $match;
                if ($pre) {
                    $block = substr($block, 1);
                }

                if (isset($params[$param])) {
                    // Part is found, replace for param value
                    $url = Stringify::replace($block, $params[$param], $url);
                } elseif ($optional && $index !== 0) {
                    // Only strip preceding slash if it's not at the base
                    $url = Stringify::remove("{$pre}{$block}", $url);
                } else {
                    // Strip matched block
                    $url = Stringify::remove($block, $url);
                }
            }
        }

        return $url;
    }

    /**
     * @inheritdoc
     */
    public function match(?string $requestUrl = null, ?string $requestMethod = null)
    {
        $params = [];

        // Set request url
        if (!$requestUrl) {
            $requestUrl = Server::isSetted('request-uri')
                ? Server::get('request-uri') : '/';
        }

        // Set request method
        if (!$requestMethod) {
            $requestMethod = Server::isSetted('REQUEST_METHOD')
                ? Server::get('REQUEST_METHOD') : 'GET';
        }

        // Strip base path from request url
        $requestUrl = substr($requestUrl, strlen($this->basePath));

        // Strip query string (?a=b) from request url
        if (($strpos = strpos($requestUrl, '?')) !== false) {
            $requestUrl = substr($requestUrl, 0, $strpos);
        }
        $lastRequestUrlChar = $requestUrl
            ? $requestUrl[strlen($requestUrl) - 1] : '';

        foreach ($this->routes as $handler) {

            list($methods, $route, $target, $name, $permission) = $handler;
            $method = (stripos($methods, $requestMethod) !== false);

            // Match
            if (!$method) {
                continue;
            }

            if ($route === '*') {
                // * Wildcard (matches all)
                $match = true;
            } elseif (isset($route[0]) && $route[0] === '@') {
                // @ regex delimiter
                $pattern = '`' . substr($route, 1) . '`u';
                $match = preg_match($pattern, $requestUrl, $params) === 1;
            } elseif (($position = strpos($route, '[')) === false) {
                // No params in url, do string comparison
                $match = strcmp($requestUrl, $route) === 0;
            } else {
                // Compare longest non-param string with url before moving on to regex
                if ((strncmp($requestUrl, $route, $position) !== 0)
                    && ($lastRequestUrlChar === '/' || $route[$position - 1] !== '/')
                ) {
                    continue;
                }
                $regex = $this->compileRoute($route);
                $match = preg_match($regex, $requestUrl, $params) === 1;
            }

            if ($match) {
                if ($params) {
                    foreach ($params as $key => $value) {
                        if (TypeCheck::isInt($key)) {
                            unset($params[$key]);
                        }
                    }
                }
                return [
                    'target'     => $target,
                    'params'     => $params,
                    'name'       => $name,
                    'permission' => $permission
                ];
            }
        }
        return false;
    }

    /**
     * Compile route regex (Expensive).
     *
     * @access protected
     * @param string $route
     * @return string
     */
    protected function compileRoute(string $route): string
    {
        if (($matches = Stringify::matchAll(static::REGEX, $route, -1, PREG_SET_ORDER))) {
            $matchTypes = $this->matchTypes;
            foreach ($matches as $match) {
                list($block, $pre, $type, $param, $optional) = $match;
                if (isset($matchTypes[$type])) {
                    $type = $matchTypes[$type];
                }
                if ($pre === '.') {
                    $pre = '\.';
                }
                $optional = $optional !== '' ? '?' : null;
                // Legacy version of PCRE require the 'P' in (?P<named>)
                $pattern = '(?:'
                    . ($pre !== '' ? $pre : null)
                    . '('
                    . ($param !== '' ? "?P<$param>" : null)
                    . $type
                    . ')'
                    . $optional
                    . ')'
                    . $optional;

                $route = Stringify::replace($block, $pattern, $route);
            }
        }
        return "`^$route$`u";
    }
}
