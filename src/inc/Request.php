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

namespace VanillePlugin\inc;

use VanillePlugin\int\RequestInterface;

class Request implements RequestInterface
{
	/**
	 * @access private
	 * @var string $baseUrl, Request base URL
	 * @var string $method, Request method
	 * @var array $headers, Request headers
	 * @var array $cookies, Request cookies
	 * @var array $body, Request body
	 * @var array $args, Request additional args
	 * @var mixed $response, Raw response
	 */
	protected $baseUrl;
	protected $method = 'GET';
	protected $headers = [];
	protected $cookies = [];
	protected $body = [];
	protected $args = [];
	protected $response = false;

	/**
	 * @param string $method
	 * @param array $args
	 * @param string $baseUrl
	 */
	public function __construct($method = 'GET', $args = [], $baseUrl = null)
	{
		$this->method = $method;
		$this->args = $args;
		$this->baseUrl = $baseUrl;
	}

	/**
	 * Set request method.
	 * 
	 * @access public
	 * @param string $method
	 * @return object
	 */
	public function setMethod($method)
	{
		$this->method = $method;
		return $this;
	}

	/**
	 * Set request base URL.
	 *
	 * @access public
	 * @param string $url
	 * @return object
	 */
	public function setBaseUrl($url)
	{
		$this->baseUrl = $url;
		return $this;
	}

	/**
	 * Set additional request args.
	 * 
	 * @access public
	 * @param array $args
	 * @return object
	 */
	public function setArgs($args = [])
	{
		$this->args = $args;
		return $this;
	}

	/**
	 * Add/Override single request arg.
	 * 
	 * @access public
	 * @param string $arg
	 * @param mixed $value
	 * @return void
	 */
	public function addArg($arg, $value = null)
	{
		$this->args[$arg] = $value;
	}

	/**
	 * Set/Reset request headers.
	 *
	 * @access public
	 * @param array $headers
	 * @return object
	 */
	public function setHeaders($headers = [])
	{
		$this->headers = $headers;
		return $this;
	}

	/**
	 * Add/Override single request header.
	 * 
	 * @access public
	 * @param string $header
	 * @param mixed $value
	 * @return void
	 */
	public function addHeader($header, $value = null)
	{
		$this->headers[$header] = $value;
	}

	/**
	 * Set/Reset request cookies.
	 *
	 * @access public
	 * @param array $cookies
	 * @return object
	 */
	public function setCookies($cookies = [])
	{
		$this->cookies = $cookies;
		return $this;
	}

	/**
	 * Add/Override single request cookie.
	 * 
	 * @access public
	 * @param string $cookie
	 * @param mixed $value
	 * @return void
	 */
	public function addCookie($cookie, $value = null)
	{
		$this->cookies[$cookie] = $value;
	}

	/**
	 * Set/Reset request body.
	 *
	 * @access public
	 * @param array $body
	 * @return object
	 */
	public function setBody($body = [])
	{
		$this->body = $body;
		return $this;
	}

	/**
	 * Add/Override single request body.
	 * 
	 * @access public
	 * @param string $body
	 * @param mixed $value
	 * @return void
	 */
	public function addBody($body, $value = null)
	{
		$this->body[$body] = $value;
	}

	/**
	 * Send request.
	 *
	 * @access public
	 * @param string $url
	 * @return object
	 */
	public function send($url = null)
	{
		$args = Arrayify::merge([
		    'method'  => $this->method,
		    'headers' => $this->headers,
		    'body'    => $this->body,
		    'cookies' => $this->cookies
		], $this->args);

		$this->response = wp_remote_request(
			"{$this->baseUrl}{$url}",
			$args
		);
		
		return $this;
	}

	/**
	 * Standalone "GET" request.
	 * 
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return object
	 */
	public function get($url, $args = [])
	{
		$this->response = wp_remote_get($url,$args);
		return $this;
	}

	/**
	 * Standalone "POST" request.
	 * 
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return object
	 */
	public function post($url, $args = [])
	{
		$this->response = wp_remote_post($url,$args);
		return $this;
	}

	/**
	 * Standalone "HEAD" request.
	 * 
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return object
	 */
	public function head($url, $args = [])
	{
		$this->response = wp_remote_head($url,$args);
		return $this;
	}

	/**
	 * Standalone "PUT" request.
	 * 
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return object
	 */
	public function put($url, $args = [])
	{
		if ( isset($args['method']) ) {
			unset($args['method']);
			$args['method'] = 'PUT';
		}

		$this->response = wp_remote_request($url,$args);
		return $this;
	}

	/**
	 * Standalone "PATCH" request.
	 * 
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return object
	 */
	public function patch($url, $args = [])
	{
		if ( isset($args['method']) ) {
			unset($args['method']);
			$args['method'] = 'PATCH';
		}

		$this->response = wp_remote_request($url,$args);
		return $this;
	}

	/**
	 * Standalone "DELETE" request.
	 * 
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return object
	 */
	public function delete($url, $args = [])
	{
		if ( isset($args['method']) ) {
			unset($args['method']);
			$args['method'] = 'DELETE';
		}
		
		$this->response = wp_remote_request($url,$args);
		return $this;
	}

	/**
	 * Get response code,
	 * Return 0 if code is not retrieved.
	 * 
	 * @access public
	 * @param void
	 * @return int
	 */
	public function getStatusCode()
	{
		return (int)wp_remote_retrieve_response_code(
			$this->response
		);
	}

	/**
	 * Get body from the raw response.
	 * 
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getBody()
	{
		return wp_remote_retrieve_body(
			$this->response
		);
	}

	/**
	 * Get header from the raw response.
	 * 
	 * @access public
	 * @param string $header
	 * @return mixed
	 */
	public function getHeader($header)
	{
		return wp_remote_retrieve_header(
			$this->response,
			$header
		);
	}

	/**
	 * Get headers from the raw response.
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public function getHeaders()
	{
		return wp_remote_retrieve_headers(
			$this->response
		);
	}

	/**
	 * Get message from the raw response.
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public function getMessage()
	{
		return wp_remote_retrieve_response_message(
			$this->response
		);
	}
	
	/**
	 * Get request base URL.
	 * 
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getBaseUrl()
	{
		return $this->baseUrl;
	}

	/**
	 * Check response error.
	 * 
	 * @access public
	 * @param void
	 * @return string
	 */
	public function hasError()
	{
		if ( $this->getStatusCode() !== 200 ) {
			return true;
		}
		return Exception::isError($this->response);
	}

	/**
	 * Get response error.
	 * 
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getError()
	{
		return Exception::getError($this->response);
	}

	/**
	 * Add arg to query.
	 * 
	 * @access public
	 * @param mixed $arg
	 * @param string $url
	 * @return string
	 */
	public static function addQueryArg($arg, $url)
	{
		return add_query_arg($arg,$url);
	}

	/**
	 * Build query args from string (Alias).
	 * 
	 * @access public
	 * @param array $args
	 * @return string
	 */
	public static function buildQuery($args)
	{
		return Stringify::buildQuery($args);
	}
}
