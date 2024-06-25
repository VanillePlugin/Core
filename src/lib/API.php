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

use VanillePlugin\inc\{
	Request, Server
};

/**
 * Plugin API helper.
 * @uses Cache
 */
class API extends Request
{
	Use \VanillePlugin\VanillePluginOption;

	/**
	 * @access public
	 */
	public const DOWN = 429;

	/**
	 * @access protected
	 * @var object $logger, API logger
	 * @var object $debug, Debug status
	 * @var object $cache, Cached response
	 */
	protected $logger;
	protected $hasDebug = false;

	/**
	 * @access private
	 * @var bool $isCached, Cache status
	 * @var mixed $data, Cache data
	 * @var string $key, Cache key
	 */
	private $isCached = false;
	private $cache = [];
	private $cacheKey;

	/**
	 * Init request client.
	 *
	 * @inheritdoc
	 */
	public function __construct(string $method = 'GET', array $args = [], ?string $baseUrl = null)
	{
		$this->method  = $method;
		$this->baseUrl = $baseUrl;

		$this->args = $this->mergeArray([
			'timeout'     => 3,
			'redirection' => 0,
			'sslverify'   => true
		], Server::maybeRequireSSL($args));

		$this->logger   = new Logger();
		$this->hasDebug = $this->hasDebug();

        // Reset config
        $this->resetConfig();
	}

	/**
	 * @inheritdoc
	 */
	public function send(?string $url = null) : self
	{
		$this->cacheKey = $this->generateKey('api', [
			'url'     => "{$this->baseUrl}{$url}",
			'method'  => $this->method,
			'headers' => $this->headers,
			'body'    => $this->body
		]);

		$this->cache = $this->getPluginCache(
			$this->cacheKey,
			$this->isCached
		);

		if ( !$this->isCached ) {
			parent::send($url);
			if ( $this->hasError() && $this->hasDebug ) {
				$this->logger->debug($this->getReport(), true);
			}
		}

		// Reset config
		$this->resetConfig();

		return $this;
	}

	/**
	 * Set <Bearer> authentication.
	 *
	 * @access public
	 * @param string $token
	 * @return void
	 */
	public function setAuth(string $token)
	{
		$this->addHeader(
			'Authorization',
			sprintf('Bearer %s', $token)
		);
	}

	/**
	 * Set <Basic> authentication.
	 *
	 * @access public
	 * @param string $user
	 * @param string $pswd
	 * @return void
	 */
	public function setBasicAuth(string $user, string $pswd)
	{
		$token = $this->base64("{$user}:{$pswd}");
		$this->addHeader(
			'Authorization',
			sprintf('Basic %s', $token)
		);
	}

	/**
	 * Get formated response from body (JSON).
	 *
	 * @access public
	 * @return array
	 */
	public function response() : array
	{
		if ( $this->isCached ) {
			return $this->cache ?: [];
		}

		$data = $this->decodeJson($this->getBody(), true);
		$this->setPluginCache($this->cacheKey, $data);

		return $data;
	}

	/**
	 * Get formated response from body (XML).
	 *
	 * @access public
	 * @return mixed
	 */
	public function responseXml()
	{
		if ( $this->isCached ) {
			return $this->cache;
		}

		$data = $this->parseXml($this->getBody());
		$this->setPluginCache($this->cacheKey, $data);

		return $data;
	}

	/**
	 * Check whether response has status (JSON).
	 *
	 * @access public
	 * @param string $status
	 * @return bool
	 */
	public function hasStatus(string $status) : bool
	{
		return $this->has('status', $status);
	}

	/**
	 * Check whether response has content (JSON).
	 *
	 * @access public
	 * @return bool
	 */
	public function hasContent() : bool
	{
		return $this->has('content');
	}

	/**
	 * Check whether response has content (JSON).
	 *
	 * @access public
	 * @param string $item
	 * @param string $value
	 * @return bool
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
	 * Check remote server status.
	 *
	 * @access public
	 * @param int $code
	 * @return bool
	 */
	public function isDown(int $code = self::DOWN) : bool
	{
		if ( !$this->getStatusCode() ) {
			return true;
		}
		if ( $this->getStatusCode() >= intval($code) ) {
			return true;
		}
		return false;
	}

	/**
	 * Disable SSL verification.
	 *
	 * @access public
	 * @return void
	 */
	public function noSSL()
	{
		$this->args['sslverify'] = false;
	}

	/**
	 * Check cache status.
	 *
	 * @access public
	 * @return bool
	 */
	public function isCached() : bool
	{
		return $this->isCached;
	}

	/**
	 * Instance API.
	 *
	 * @access public
	 * @param string $name
	 * @param string $path
	 * @param mixed $args
	 * @return mixed
	 */
	public static function instance(string $name, $path = 'api', ...$args)
	{
		return (new Loader())->i($path, $name, ...$args);
	}
}
