<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.6
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\int;

interface RequestInterface
{
    /**
     * @param string $method
     * @param array $params
     * @param string $baseUrl null
     * @return void
     */
    function __construct($method = 'GET', $params = [], $baseUrl = null);

    /**
     * @param string $param
     * @param mixed $value
     * @return void
     */
    function addParameter($param,$value);

    /**
     * @param array $headers
     * @return object
     */
    function setHeaders($headers = []);

    /**
     * @param array $cookies
     * @return object
     */
    function setCookies($cookies = []);

    /**
     * @param array $body
     * @return object
     */
    function setBody($body = []);

    /**
     * @param array $method
     * @return object
     */
    function setMethod($method = 'GET');

    /**
     * @param string $url
     * @return object
     */
    function setBaseUrl($url);

    /**
     * @param void
     * @return object
     */
    function send($url = null);

    /**
     * @param string $url
     * @param array $args
     * @return object
     */
    function post($url, $args = []);

    /**
     * @param string $url
     * @param array $args
     * @return object
     */
    function get($url, $args = []);

    /**
     * @param string $url
     * @param array $args
     * @return object
     */
    function head($url, $args = []);

    /**
     * @param void
     * @return int
     */
    function getStatusCode();

    /**
     * @param void
     * @return string
     */
    function getBody();
}
