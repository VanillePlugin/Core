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

namespace VanillePlugin\inc;

use \WP_REST_Server as RestfulServer;
use \WP_REST_Request as RestfulRequest;
use \WP_REST_Response as RestfulResponse;

final class Restful
{
	/**
	 * @access public
	 */
	public const READABLE  = RestfulServer::READABLE;
	public const CREATABLE = RestfulServer::CREATABLE;
	public const EDITABLE  = RestfulServer::EDITABLE;
	public const DELETABLE = RestfulServer::DELETABLE;
	public const METHODS   = RestfulServer::ALLMETHODS;

	/**
     * Register route.
     *
	 * @access public
	 * @param string $namespace
	 * @param string $route
	 * @param array $args
	 * @param bool $override
	 * @return bool
	 */
	public static function register(string $namespace, string $route, array $args, bool $override = false) : bool
	{
	    return register_rest_route($namespace, $route, Format::restful($args), $override);
	}

	/**
     * Send response.
     *
	 * @access public
	 * @param mixed $data
	 * @param int $code
	 * @param array $headers
	 * @return RestfulResponse
	 */
	public static function response($data = [], int $code = 200, array $headers = []) : RestfulResponse
	{
	    return new RestfulResponse($data, $code, $headers);
	}

	/**
     * Send error.
     *
	 * @access public
	 * @param int $code
	 * @param string $message
	 * @param mixed $data
	 * @return object
	 */
	public static function error(int $code = 403, ?string $message = null, $data = []) : object
	{
		$code = ($code < 400) ? 400 : $code;
		if ( !$message ) {
			$message = Status::getMessage($code);
		}
		$data = Arrayify::merge(['status' => $code], $data);
		return Exception::error($code, $message, $data);
	}

	/**
     * Send request.
     *
	 * @access public
	 * @param string $method
	 * @param string $route
	 * @param array $atts
	 * @return RestfulRequest
	 */
	public static function request(string $method, string $route, array $atts = []) : RestfulRequest
	{
	    return new RestfulRequest($method, $route, $atts);
	}

	/**
     * Fetch response body.
     *
	 * @access public
	 * @param string $method
	 * @param string $route
	 * @param array $atts
	 * @return string
	 */
	public static function fetch(string $method, string $route, array $atts = []) : string
	{
		$request = self::request($method, $route, $atts);
	    return self::getBody($request);
	}

	/**
     * Check response error.
     *
	 * @access public
	 * @param RestfulResponse $response
	 * @return bool
	 */
	public static function isError(RestfulResponse $response) : bool
	{
	    return $response->is_error();
	}

	/**
     * Get request route.
     *
	 * @access public
	 * @param RestfulRequest $request
	 * @return string
	 */
	public static function getRoute(RestfulRequest $request) : string
	{
		return $request->get_route();
	}

	/**
     * Get request attributes.
     *
	 * @access public
	 * @param RestfulRequest $request
	 * @return array
	 */
	public static function getAttributes(RestfulRequest $request) : array
	{
		return $request->get_attributes();
	}

	/**
     * Get request params.
     *
	 * @access public
	 * @param RestfulRequest $request
	 * @return array
	 */
	public static function getParams(RestfulRequest $request) : array
	{
		return $request->get_params();
	}

	/**
     * Get request body content.
     *
	 * @access public
	 * @param RestfulRequest $request
	 * @return string
	 */
	public static function getBody(RestfulRequest $request) : string
	{
		return (string)$request->get_body();
	}

	/**
     * Get request body parameters (POST).
     *
	 * @access public
	 * @param RestfulRequest $request
	 * @return array
	 */
	public static function getBodyParams(RestfulRequest $request) : array
	{
		return $request->get_body_params();
	}

	/**
     * Get request query parameters (GET).
     *
	 * @access public
	 * @param RestfulRequest $request
	 * @return array
	 */
	public static function getQueryParams(RestfulRequest $request) : array
	{
		return $request->get_query_params();
	}

	/**
     * Get request file parameters (FILES).
     *
	 * @access public
	 * @param RestfulRequest $request
	 * @return array
	 */
	public static function getFileParams(RestfulRequest $request) : array
	{
		return $request->get_file_params();
	}

	/**
     * Get request url parameters (URL).
     *
	 * @access public
	 * @param RestfulRequest $request
	 * @return array
	 */
	public static function getUrlParams(RestfulRequest $request) : array
	{
		return $request->get_url_params();
	}

	/**
     * Get request headers.
     *
	 * @access public
	 * @param RestfulRequest $request
	 * @return array
	 */
	public static function getHeaders(RestfulRequest $request) : array
	{
		return $request->get_headers();
	}

	/**
     * Get request method.
     *
	 * @access public
	 * @param RestfulRequest $request
	 * @return string
	 */
	public static function getMethod(RestfulRequest $request) : string
	{
		return $request->get_method();
	}

	/**
     * Check request parameter.
     *
	 * @access public
	 * @param RestfulRequest $request
	 * @param string $key
	 * @return bool
	 */
	public static function hasParam(RestfulRequest $request, string $key) : bool
	{
		return $request->has_param($key);
	}

	/**
     * Check valid request parameter.
     *
	 * @access public
	 * @param RestfulRequest $request
	 * @param string $key
	 * @return bool
	 */
	public static function isValidParam(RestfulRequest $request, string $key) : bool
	{
		return $request->has_param($key);
	}
}
