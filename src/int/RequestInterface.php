<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface RequestInterface
{
	/**
	 * Set request method.
	 *
	 * @param string $method
	 * @return object
	 */
	function setMethod(string $method) : self;

	/**
	 * Set request base URL.
	 
	 * @param string $url
	 * @return object
	 */
	function setBaseUrl(string $url) : self;

	/**
	 * Set additional request args.
	 *
	 * @param array $args
	 * @return object
	 */
	function setArgs(array $args = []) : self;

	/**
	 * Add or override single request arg.
	 *
	 * @param string $arg
	 * @param mixed $value
	 * @return void
	 */
	function addArg(string $arg, $value = null);

	/**
	 * Set or reset request headers.
	 *
	 * @param array $headers
	 * @return object
	 */
	function setHeaders(array $headers = []) : self;

	/**
	 * Add or override single request header.
	 *
	 * @param string $header
	 * @param mixed $value
	 * @return void
	 */
	function addHeader(string $header, $value = null);

	/**
	 * Set or reset request cookies.
	 *
	 * @param array $cookies
	 * @return object
	 */
	function setCookies(array $cookies = []) : self;

	/**
	 * Add or override single request cookie.
	 *
	 * @param string $cookie
	 * @param mixed $value
	 * @return void
	 */
	function addCookie(string $cookie, $value = null);

	/**
	 * Set or reset request body.
	 *
	 * @param array $body
	 * @return object
	 */
	function setBody(array $body = []) : self;

	/**
	 * Add or override single request body.
	 *
	 * @param string $body
	 * @param mixed $value
	 * @return void
	 */
	function addBody(string $body, $value = null);

	/**
	 * Send request.
	 *
	 * @param string $url
	 * @return object
	 */
	function send(?string $url = null) : self;

	/**
	 * Standalone "GET" request.
	 *
	 * @param string $url
	 * @param array $args
	 * @return object
	 */
	function get(string $url, array $args = []) : self;

	/**
	 * Standalone "POST" request.
	 *
	 * @param string $url
	 * @param array $args
	 * @return object
	 */
	function post(string $url, array $args = []) : self;

	/**
	 * Standalone "HEAD" request.
	 *
	 * @param string $url
	 * @param array $args
	 * @return object
	 */
	function head(string $url, array $args = []) : self;

	/**
	 * Standalone "PUT" request.
	 *
	 * @param string $url
	 * @param array $args
	 * @return object
	 */
	function put(string $url, array $args = []) : self;

	/**
	 * Standalone "PATCH" request.
	 *
	 * @param string $url
	 * @param array $args
	 * @return object
	 */
	function patch(string $url, array $args = []) : self;

	/**
	 * Standalone "DELETE" request.
	 *
	 * @param string $url
	 * @param array $args
	 * @return object
	 */
	function delete(string $url, array $args = []) : self;

	/**
	 * Get response code,
	 * Return 0 if code is not retrieved.
	 *
	 * @return int
	 */
	function getStatusCode() : int;

	/**
	 * Get body from the raw response.
	 *
	 * @return string
	 */
	function getBody() : string;

	/**
	 * Get header from the raw response.
	 *
	 * @param string $header
	 * @return string
	 */
	function getHeader(string $header) : string;

	/**
	 * Get headers from the raw response.
	 *
	 * @return mixed
	 */
	function getHeaders();

	/**
	 * Get message from the raw response.
	 *
	 * @return string
	 */
	function getMessage() : string;

	/**
	 * Check response error.
	 *
	 * @return bool
	 */
	function hasError() : bool;

	/**
	 * Get response error.
	 *
	 * @return mixed
	 */
	function getError();

	/**
	 * Get request report.
	 *
	 * @return array
	 */
	function getReport() : array;

	/**
	 * Add arg to query.
	 *
	 * @param mixed $arg
	 * @param string $url
	 * @return string
	 */
	static function addQueryArg($arg, string $url) : string;
}
