<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.3
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
     * @param array $routes
     * @param string $basePath
     * @param array $matchTypes
     */
    function __construct($routes = [], $basePath = '', $matchTypes = []);

    /**
     * @param void
     * @return array
     */
    function getRoutes();

    /**
     * @param array $routes
     * @return void
     */
    function addRoutes($routes);

    /**
     * @param string $basePath
     * @return void
     */
    function setBasePath($basePath);

    /**
     * @param array $matchTypes
     * @return void
     */
    function addMatchTypes($matchTypes);

    /**
     * @param string $routeName
     * @param array @params
     * @return string
     * @throws Exception
     */
    function generate($routeName, $params = []);

    /**
     * @param string $requestUrl
     * @param string $requestMethod
     * @return mixed
     */
    function match($requestUrl = null, $requestMethod = null);
}
