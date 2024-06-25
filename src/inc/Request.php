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
	 * @var string $endpoint, Request endpoint
	 * @var string $method, Request method
	 * @var array $headers, Request headers
	 * @var array $cookies, Request cookies
	 * @var array $body, Request body
	 * @var array $args, Request additional args
	 * @var mixed $response, Raw response
	 */
	protected $baseUrl;
	protected $endpoint;
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
		$this->method  = $method;
		$this->args    = $args;
		$this->baseUrl = $baseUrl;
	}

	/**
	 * @inheritdoc
	 */
	public function setMethod(string $method) : self
	{
		$this->method = $method;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function setBaseUrl(string $url) : self
	{
		$this->baseUrl = $url;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function setArgs(array $args = []) : self
	{
		$this->args = $args;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addArg(string $arg, $value = null)
	{
		$this->args[$arg] = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function setHeaders(array $headers = []) : self
	{
		$this->headers = $headers;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addHeader(string $header, $value = null)
	{
		$this->headers[$header] = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function setCookies(array $cookies = []) : self
	{
		$this->cookies = $cookies;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addCookie(string $cookie, $value = null)
	{
		$this->cookies[$cookie] = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function setBody(array $body = []) : self
	{
		$this->body = $body;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addBody(string $body, $value = null)
	{
		$this->body[$body] = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function send(?string $url = null) : self
	{
		$this->args = Arrayify::merge([
		    'method'  => $this->method,
		    'headers' => $this->headers,
		    'body'    => $this->body,
		    'cookies' => $this->cookies
		], $this->args);

		$this->endpoint = "{$this->baseUrl}{$url}";
		$this->response = wp_remote_request(
			$this->endpoint,
			$this->args
		);
		
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function get(string $url, array $args = []) : self
	{
		$this->response = wp_remote_get($url, $args);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function post(string $url, array $args = []) : self
	{
		$this->response = wp_remote_post($url, $args);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function head(string $url, array $args = []) : self
	{
		$this->response = wp_remote_head($url, $args);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function put(string $url, array $args = []) : self
	{
		$args['method'] = 'PUT';
		$this->response = wp_remote_request($url, $args);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function patch(string $url, array $args = []) : self
	{
		$args['method'] = 'PATCH';
		$this->response = wp_remote_request($url, $args);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function delete(string $url, array $args = []) : self
	{
		$args['method'] = 'DELETE';
		$this->response = wp_remote_request($url, $args);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getStatusCode() : int
	{
		return (int)wp_remote_retrieve_response_code(
			$this->response
		);
	}

	/**
	 * @inheritdoc
	 */
	public function getBody() : string
	{
		return wp_remote_retrieve_body(
			$this->response
		);
	}

	/**
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
	 * @inheritdoc
	 */
	public function getHeaders()
	{
		return wp_remote_retrieve_headers(
			$this->response
		);
	}

	/**
	 * @inheritdoc
	 */
	public function getMessage() : string
	{
		return wp_remote_retrieve_response_message(
			$this->response
		);
	}

	/**
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
	 * @inheritdoc
	 */
	public function getError()
	{
		return Exception::getError($this->response);
	}

	/**
	 * @inheritdoc
	 */
	public function getReport() : array
	{
		return [
			'request' => [
				'url'      => $this->baseUrl,
				'endpoint' => $this->endpoint,
				'method'   => $this->method,
				'headers'  => $this->headers,
				'body'     => $this->body
			],
			'response' => [
				'code'    => $this->getStatusCode(),
				'message' => $this->getMessage(),
				'error'   => $this->getError()
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function addQueryArg($arg, string $url) : string
	{
		return add_query_arg($arg, $url);
	}
}
