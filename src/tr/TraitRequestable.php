<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\{
    HttpRequest, HttpPost, HttpGet,
	Response, Request, Server, Stringify
};

trait TraitRequestable
{
	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getRequest(?string $key = null)
    {
        return HttpRequest::get($key);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasRequest(?string $key = null) : bool
    {
        return HttpRequest::isSetted($key);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getHttpPost(?string $key = null)
    {
        return HttpPost::get($key);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasHttpPost(?string $key = null) : bool
    {
        return HttpPost::isSetted($key);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getHttpGet(?string $key = null)
    {
        return HttpGet::get($key);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasHttpGet(?string $key = null) : bool
    {
        return HttpGet::isSetted($key);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function setHttpResponse(string $msg, $content = [], string $status = 'success', int $code = 200)
	{
		Response::set($msg, $content, $status, $code);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getServer(?string $key = null, $format = true)
    {
        return Server::get($key, $format);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getServerBaseUrl() : string
    {
        return Server::getBaseUrl();
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getServerCurrentUrl($escape = false) : string
	{
		return Server::getCurrentUrl($escape);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getServerProtocol() : string
    {
        return Server::getProtocol();
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getServerIp(?string $domain = null)
	{
		return Server::getIp($domain);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function isBasicAuth() : bool
	{
		return Server::isBasicAuth();
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getBasicAuthUser() : string
	{
		return Server::getBasicAuthUser();
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getBasicAuthPwd() : string
	{
		return Server::getBasicAuthPwd();
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function getBearerToken() : string
    {
        return Server::getBearerToken();
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function redirect(string $location, int $status = 301)
	{
		Server::redirect($location, $status);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function isSsl() : bool
	{
		return Server::isSsl();
	}

    /**
     * Check if SSL verify is required in request.
     * 
	 * @access protected
	 * @inheritdoc
     */
    protected function maybeRequireSSL(array $args) : array
    {
    	return Server::maybeRequireSSL($args);
    }

    /**
     * Parse URL.
     * 
	 * @access protected
	 * @inheritdoc
     */
    protected function parseUrl(string $url, int $component = -1)
    {
    	return Stringify::parseUrl($url, $component);
    }

    /**
     * Build query args from string.
     * 
	 * @access protected
	 * @inheritdoc
     */
    protected function buildQuery($args, string $prefix = '', ?string $sep = '&', int $enc = 1) : string
    {
    	return Stringify::buildQuery($args, $prefix, $sep, $enc);
    }

	/**
	 * Get HTTP client.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function getHttpClient(string $method = 'GET', array $args = [], ?string $baseUrl = null) : Request
    {
        return new Request($method, $args, $baseUrl);
    }
}
