<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

class Request extends WordPress
{
	/**
	 * @access private
	 */
	private $baseUrl;
	private $method = 'GET';
	private $params = [];
	private $headers = [];
	private $cookies = [];
	private $body = [];
	private $raw;

	/**
	 * @param string $method
	 * @param array $params
	 * @return void
	 */
	public function __construct($method = 'GET', $params = [], $baseUrl = null)
	{
		$this->method = $method;
		$this->params = $params;
		$this->baseUrl = $baseUrl;
	}

	/**
	 * @access public
	 * @param string $param
	 * @param mixed $value
	 * @return void
	 */
	public function addParam($param,$value)
	{
		$this->params[$param] = $value;
	}

	/**
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
	 * @access public
	 * @param array $method
	 * @return object
	 */
	public function setMethod($method = 'GET')
	{
		$this->method = $method;
		return $this;
	}

	/**
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
	 * @access public
	 * @param void
	 * @return object
	 */
	public function send($url = null)
	{
		$this->raw = wp_remote_request("{$this->baseUrl}{$url}", array_merge([
		    'method'  => $this->method,
		    'headers' => $this->headers,
		    'body'    => $this->body,
		    'cookies' => $this->cookies
		], $this->params));
		return $this;
	}

	/**
	 * @access public
	 * @param void
	 * @return string|json
	 */
	public function getStatusCode()
	{
		if (isset($this->raw->errors)) {
			return 500;
		}
		return $this->raw['response']['code'];
	}

	/**
	 * @access public
	 * @param void
	 * @return string|json
	 */
	public function getBody()
	{
		return wp_remote_retrieve_body($this->raw);
	}
}
