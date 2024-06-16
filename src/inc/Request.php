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
	 * @inheritdoc
	 */
	public function __construct(string $method = 'GET', array $args = [], ?string $baseUrl = null)
	{
		$this->method = $method;
		$this->args = $args;
		$this->baseUrl = $baseUrl;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function setMethod(string $method) : self
	{
		$this->method = $method;
		return $this;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function setBaseUrl(string $url) : self
	{
		$this->baseUrl = $url;
		return $this;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function setArgs(array $args = []) : self
	{
		$this->args = $args;
		return $this;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function addArg(string $arg, $value = null)
	{
		$this->args[$arg] = $value;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function setHeaders(array $headers = []) : self
	{
		$this->headers = $headers;
		return $this;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function addHeader(string $header, $value = null)
	{
		$this->headers[$header] = $value;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function setCookies(array $cookies = []) : self
	{
		$this->cookies = $cookies;
		return $this;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function addCookie(string $cookie, $value = null)
	{
		$this->cookies[$cookie] = $value;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function setBody(array $body = []) : self
	{
		$this->body = $body;
		return $this;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function addBody(string $body, $value = null)
	{
		$this->body[$body] = $value;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function send(?string $url = null) : self
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
	 * @access public
	 * @inheritdoc
	 */
	public function get(string $url, array $args = []) : self
	{
		$this->response = wp_remote_get($url, $args);
		return $this;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function post(string $url, array $args = []) : self
	{
		$this->response = wp_remote_post($url, $args);
		return $this;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function head(string $url, array $args = []) : self
	{
		$this->response = wp_remote_head($url, $args);
		return $this;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function put(string $url, array $args = []) : self
	{
		if ( isset($args['method']) ) {
			unset($args['method']);
			$args['method'] = 'PUT';
		}

		$this->response = wp_remote_request($url, $args);
		return $this;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function patch(string $url, array $args = []) : self
	{
		if ( isset($args['method']) ) {
			unset($args['method']);
			$args['method'] = 'PATCH';
		}

		$this->response = wp_remote_request($url, $args);
		return $this;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function delete(string $url, array $args = []) : self
	{
		if ( isset($args['method']) ) {
			unset($args['method']);
			$args['method'] = 'DELETE';
		}
		
		$this->response = wp_remote_request($url, $args);
		return $this;
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function getStatusCode() : int
	{
		return (int)wp_remote_retrieve_response_code(
			$this->response
		);
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function getBody() : string
	{
		return wp_remote_retrieve_body(
			$this->response
		);
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function getHeader(string $header) : string
	{
		return wp_remote_retrieve_header(
			$this->response,
			$header
		);
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function getHeaders()
	{
		return wp_remote_retrieve_headers(
			$this->response
		);
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function getMessage() : string
	{
		return wp_remote_retrieve_response_message(
			$this->response
		);
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function hasError() : bool
	{
		if ( $this->getStatusCode() !== 200 ) {
			return true;
		}
		return Exception::isError($this->response);
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public function getError()
	{
		return Exception::getError($this->response);
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public static function addQueryArg($arg, string $url) : string
	{
		return add_query_arg($arg, $url);
	}

	/**
	 * @access public
	 * @inheritdoc
	 */
	public static function buildQuery(array $args) : string
	{
		return Stringify::buildQuery($args);
	}
}
