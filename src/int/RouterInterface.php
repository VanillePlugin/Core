<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface RouterInterface
{
    /**
     * Init router.
     *
     * @param array $routes
     * @param string $basePath
     * @param array $matchTypes
     */
    function __construct(array $routes = [], string $basePath = '', array $matchTypes = []);

    /**
     * Get routes.
     *
     * @return array
     */
    function getRoutes() : array;

    /**
     * Add routes,
     * Using [[$method, $route, $target, $name]].
     *
     * @param array $routes
     * @return void
     */
    function addRoutes(array $routes);

    /**
     * Set base path.
     *
     * @param string $basePath
     * @return void
     */
    function setBasePath(string $basePath);

    /**
     * Add named match types.
     *
     * @param array $matchTypes
     * @return void
     */
    function addMatchTypes(array $matchTypes);

    /**
     * Map route to controller,
     * (GET|POST|PATCH|PUT|DELETE),
     * Custom regex must start with an '@'.
     *
     * @param string $method
     * @param string $route
     * @param mixed $controller
     * @param string $name
     * @param mixed $permission
     * @return void
     * @throws RuntimeException
     */
    function map(string $method, string $route, $controller, ?string $name = null, $permission = null);

    /**
     * Reversed routing,
     * Generate named route URL.
     *
     * @param string $routeName
     * @param array $params
     * @return string
     * @throws RuntimeException
     */
    function generate(string $routeName, array $params = []) : string;

    /**
     * Match request URL against routes.
     *
     * @param string $requestUrl
     * @param string $requestMethod
     * @return mixed
     */
    function match(?string $requestUrl = null, ?string $requestMethod = null);
}
