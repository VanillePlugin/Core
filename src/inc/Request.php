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

class Request
{
	/**
	 * @access public
	 */
	public const GET     = 'GET';
	public const POST    = 'POST';
	public const HEAD    = 'HEAD';
	public const PUT     = 'PUT';
	public const PATCH   = 'PATCH';
	public const OPTIONS = 'OPTIONS';
	public const DELETE  = 'DELETE';

	/**
	 * Send HTTP request.
	 *
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return mixed
	 */
	public static function do(string $url, array $args)
	{
		return wp_remote_request($url, $args);
	}

	/**
	 * HTTP GET request.
	 *
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return mixed
	 */
	public static function get(string $url, array $args = [])
	{
		return wp_remote_get($url, Format::request($args));
	}

	/**
	 * HTTP POST request.
	 *
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return mixed
	 */
	public static function post(string $url, array $args = [])
	{
		return wp_remote_post($url, Format::request($args));
	}

	/**
	 * HTTP HEAD request.
	 *
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return mixed
	 */
	public static function head(string $url, array $args = [])
	{
		return wp_remote_head($url, Format::request($args));
	}

	/**
	 * HTTP PUT request.
	 *
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return mixed
	 */
	public static function put(string $url, array $args = [])
	{
		$args['method'] = static::PUT;
		return self::do($url, Format::request($args));
	}

	/**
	 * HTTP PATCH request.
	 *
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return mixed
	 */
	public static function patch(string $url, array $args = [])
	{
		$args['method'] = static::PATCH;
		return self::do($url, Format::request($args));
	}

	/**
	 * HTTP OPTIONS request.
	 *
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return mixed
	 */
	public static function options(string $url, array $args = [])
	{
		$args['method'] = static::OPTIONS;
		return self::do($url, Format::request($args));
	}

	/**
	 * HTTP DELETE request.
	 *
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return mixed
	 */
	public static function delete(string $url, array $args = [])
	{
		$args['method'] = static::DELETE;
		return self::do($url, Format::request($args));
	}

	/**
	 * Get response status code.
	 *
	 * @access public
	 * @param mixed $response
	 * @return mixed
	 */
	public static function getStatusCode($response): int
	{
		return (int)wp_remote_retrieve_response_code(
			$response
		);
	}

	/**
	 * Get response body.
	 *
	 * @access public
	 * @param mixed $response
	 * @return string
	 */
	public static function getBody($response): string
	{
		return wp_remote_retrieve_body(
			$response
		);
	}

	/**
	 * Get response header.
	 *
	 * @access public
	 * @param string $header
	 * @param mixed $response
	 * @return string
	 */
	public static function getHeader(string $header, $response): string
	{
		return wp_remote_retrieve_header(
			$response,
			$header
		);
	}

	/**
	 * Get response headers.
	 *
	 * @access public
	 * @param mixed $response
	 * @return mixed
	 */
	public static function getHeaders($response)
	{
		return wp_remote_retrieve_headers(
			$response
		);
	}

	/**
	 * Get response message.
	 *
	 * @access public
	 * @param mixed $response
	 * @return string
	 */
	public static function getMessage($response): string
	{
		return wp_remote_retrieve_response_message(
			$response
		);
	}

	/**
	 * URL query string.
	 *
	 * @access public
	 * @param array $args
	 * @param string $url
	 * @return string
	 */
	public static function queryUrl(array $args, string $url): string
	{
		return add_query_arg($args, $url);
	}
}
