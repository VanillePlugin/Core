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

namespace VanillePlugin\tr;

use VanillePlugin\inc\{
	HttpRequest,
	HttpPost,
	HttpGet,
	Response,
	Server,
	Stringify,
	Upload
};

/**
 * Define HTTP functions.
 */
trait TraitRequestable
{
	/**
	 * Get request value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getRequest(?string $key = null)
	{
		return HttpRequest::get($key);
	}

	/**
	 * Check request value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function hasRequest(?string $key = null): bool
	{
		return HttpRequest::isSetted($key);
	}

	/**
	 * Get HTTP POST value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getHttpPost(?string $key = null)
	{
		return HttpPost::get($key);
	}

	/**
	 * Check HTTP POST value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function hasHttpPost(?string $key = null): bool
	{
		return HttpPost::isSetted($key);
	}

	/**
	 * Get HTTP GET value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getHttpGet(?string $key = null)
	{
		return HttpGet::get($key);
	}

	/**
	 * Get HTTP GET value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function hasHttpGet(?string $key = null): bool
	{
		return HttpGet::isSetted($key);
	}

	/**
	 * Get blob value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getBlob(?string $key = null)
	{
		return Upload::get($key);
	}

	/**
	 * Check blob value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function hasBlob(?string $key = null): bool
	{
		return Upload::isSetted($key);
	}

	/**
	 * Get server value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getServer(?string $key = null)
	{
		return Server::get($key);
	}

	/**
	 * Check server value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function hasHttpServer(?string $key = null): bool
	{
		return Server::isSetted($key);
	}

	/**
	 * Get base URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getServerBaseUrl(): string
	{
		return Server::getBaseUrl();
	}

	/**
	 * Get current URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getServerCurrentUrl($escape = false): string
	{
		return Server::getCurrentUrl($escape);
	}

	/**
	 * Get protocol.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getServerProtocol(): string
	{
		return Server::getProtocol();
	}

	/**
	 * Get remote IP address.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getServerIp(?string $domain = null)
	{
		return Server::getIp($domain);
	}

	/**
	 * Check basic authentication.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isBasicAuth(): bool
	{
		return Server::isBasicAuth();
	}

	/**
	 * Get basic authentication user.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getBasicAuthUser(): string
	{
		return Server::getBasicAuthUser();
	}

	/**
	 * Get basic authentication password.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getBasicAuthPwd(): string
	{
		return Server::getBasicAuthPwd();
	}

	/**
	 * Get authorization token.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getBearerToken(): string
	{
		return Server::getBearerToken();
	}

	/**
	 * Check whether protocol is HTTPS (SSL).
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isSsl(): bool
	{
		return Server::isSsl();
	}

	/**
	 * Check if SSL verify is required (SNI).
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function mayRequireSSL(bool $verify = true): bool
	{
		return Server::mayRequireSSL($verify);
	}

	/**
	 * Get domain name from URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getDomainName(?string $url = null): string
	{
		return Server::getDomain($url);
	}

	/**
	 * Parse base from URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function parseBaseUrl(string $url): string
	{
		return Server::parseBaseUrl($url);
	}

	/**
	 * Parse URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function parseUrl(string $url, int $component = -1)
	{
		return Stringify::parseUrl($url, $component);
	}

	/**
	 * Set HTTP response.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function setHttpResponse(string $message, $content = [], string $status = 'success', int $code = 200)
	{
		Response::set($message, $content, $status, $code);
	}

	/**
	 * Redirect request.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function redirect(string $location, int $status = 301)
	{
		Server::redirect($location, $status);
	}
}
