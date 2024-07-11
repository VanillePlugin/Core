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

namespace VanillePlugin\lib;

use VanillePlugin\inc\Request;
use VanillePlugin\int\RequestInterface;

/**
 * Plugin API helper.
 * @uses Cache
 */
class API extends Request implements RequestInterface
{
	Use \VanillePlugin\VanillePluginOption,
	    \VanillePlugin\tr\TraitThrowable;

	/**
	 * @access public
	 */
	public const UP   = 200;
	public const DOWN = 400;

	/**
	 * @access protected
	 * @var string $url, Request base URL
	 * @var string $method, Request method
	 * @var array $headers, Request headers
	 * @var array $cookies, Request cookies
	 * @var array $body, Request body
	 * @var array $args, Request additional args
	 * @var array $auth, Request auth
	 * @var mixed $response, Raw response
	 * @var bool $debug, Debug status
	 * @var ?Logger $logger, Request logger
	 */
	protected $url;
	protected $method = self::GET;
	protected $headers = [];
	protected $cookies = [];
	protected $body = [];
	protected $args = [];
	protected $auth = [];
	protected $response = false;
	protected $debug = false;
	protected ?Logger $logger = null;

	/**
	 * Init API request.
	 *
	 * @param string $method
	 * @param array $args
	 * @param string $url
	 */
	public function __construct(string $method = self::GET, array $args = [], ?string $url = null)
	{
		// Filter request SSL
		$this->addFilter('http-request-args', [$this, 'filterSSL'], 20);

		// Default args
		$this->method = $method;
		$this->args   = $args;
		$this->url    = $url;

		// Default auth
		$this->auth = $this->mergeArray([
			'token' => false,
			'user'  => false,
			'pswd'  => false
		], $this->auth);

		// Debug
		if ( ($this->debug = $this->hasDebug()) ) {
			$this->logger = new Logger();
		}
	}

	/**
	 * @inheritdoc
	 */
	public function send(?string $url = null) : self
	{
		$url = $this->formatPath("{$this->url}{$url}");
	
		if ( ($token = $this->getAuth($type)) ) {
			($type == 'basic') 
			? $this->setBasicAuth($token)
			: $this->setAuth($token);
		}

		$this->args = $this->mergeArray([
			'method'      => $this->method,
			'headers'     => $this->headers,
			'body'        => $this->body,
			'cookies'     => $this->cookies,
			'timeout'     => $this->timeout(),
			'user-agent'  => $this->userAgent(),
			'sslverify'   => $this->mayRequireSSL(true),
			'redirection' => 0
		], $this->args);

		$key = $this->toKey($this);
		$this->response = $this->getPluginCache($key, $status);

		if ( !$status ) {
			$this->response = self::do($url, $this->args);
			$this->setPluginCache($key, $this->response);
		}
		
		if ( $this->hasError() && $this->debug ) {
			$this->logger->debug($this, true);
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function noSSL() : self
	{
		$this->args['sslverify'] = false;
		return $this;
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
		$this->url = $url;
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
	public function filterSSL($args) : array
	{
		if ( isset($args['reject-unsafe-urls']) ) {
			$ssl = $this->applyPluginFilter('request-ssl', $this->isSsl());
			$args['reject-unsafe-urls'] = $ssl;
		}
		return $args;
	}

	/**
	 * @inheritdoc
	 */
	public function setAuth(string $token)
	{
		$this->addHeader(
			'Authorization',
			sprintf('Bearer %s', $token)
		);
	}

	/**
	 * @inheritdoc
	 */
	public function setBasicAuth(string $user, ?string $pswd = null)
	{
		$token = ($pswd) ? $this->base64("{$user}:{$pswd}") : $user;
		$this->addHeader(
			'Authorization',
			sprintf('Basic %s', $token)
		);
	}

	/**
	 * @inheritdoc
	 */
	public function response() : array
	{
		$data = [];
		if ( !$this->hasError() ) {
			$body = $this->body();
			$data = (array)$this->decodeJson($body, true);
		}
		return $data;
	}

	/**
	 * @inheritdoc
	 */
	public function responseXml() : array
	{
		$data = [];
		if ( !$this->hasError() ) {
			$body = $this->body();
			$data = (array)$this->parseXml($body);
		}
		return $data;
	}

	/**
	 * @inheritdoc
	 */
	public function body() : string
	{
		return self::getBody($this->response);
	}

	/**
	 * @inheritdoc
	 */
	public function status() : int
	{
		return self::getStatusCode($this->response);
	}

	/**
	 * @inheritdoc
	 */
	public function hasStatus(string $status) : bool
	{
		return $this->has('status', $status);
	}

	/**
	 * @inheritdoc
	 */
	public function hasContent() : bool
	{
		return $this->has('content');
	}

	/**
	 * @inheritdoc
	 */
	public function has(string $item, ?string $value = null) : bool
	{
		$response = $this->response();
		if ( isset($response[$item]) ) {
			if ( $value ) {
				return ($response[$item] == $value);
			}
			return !empty($response[$item]);
		}
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function timeout() : int
	{
		$timeout = $this->getTimeout();
		return $this->applyPluginFilter('request-timeout', $timeout);
	}

	/**
	 * @inheritdoc
	 */
	public function userAgent() : string
	{
		$plugin = $this->getPluginHeader(
			$this->getMainFile()
		);
		$version = $plugin['Version'] ?? false;
		if ( !$version ) {
			$version = $this->getPluginVersion() ?: 'na';
		}

		$wp  = $this->getSiteVersion() ?: 'na';
		$php = phpversion() ?: 'na';

		$ua = [
			"wp/{$wp}",
			"php/{$php}",
			"{$this->getNameSpace()}/{$version}"
		];

		$ua = implode(';', $ua);
		return $this->applyPluginFilter('request-ua', $ua);
	}

	/**
	 * @inheritdoc
	 */
	public function isDown(?int $code = null) : bool
	{
		$code = ($code) ? $code : static::DOWN;
		if ( !$this->status() || ($this->status() >= $code) ) {
			return true;
		}
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function hasError() : bool
	{
		if ( !$this->inArray($this->status(), (array)static::UP) ) {
			return true;
		}
		return $this->isError($this->response);
	}

	/**
	 * @inheritdoc
	 */
	public function error()
	{
		if ( !$this->inArray($this->status(), (array)static::UP) ) {
			return self::getMessage($this->response);
		}
		return $this->getError($this->response);
	}

	/**
	 * @inheritdoc
	 */
	public static function instance(string $name, $path = 'api', ...$args)
	{
		return (new Loader())->i($path, $name, ...$args);
	}

	/**
	 * Get API auth.
	 *
	 * @access protected
	 * @param string $type
	 * @return mixed
	 */
	protected function getAuth(?string &$type = null)
	{
		if ( $this->auth['token'] ) {
			$type = 'bearer';
			return $this->auth['token'];
		}

		if ( $this->auth['user'] && $this->auth['pswd'] ) {
			$type = 'basic';
			$auth = "{$this->auth['user']}:{$this->auth['pswd']}";
			return $this->base64($auth);
		}

		return false;
	}
}
