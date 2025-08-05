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

namespace VanillePlugin\int;

interface RequestInterface
{
	/**
	 * Set request method.
	 *
	 * @param string $method
	 * @return object
	 */
	function setMethod(string $method): self;

	/**
	 * Set request base URL.
	 *
	 * @param string $url
	 * @return object
	 */
	function setBaseUrl(string $url): self;

	/**
	 * Set additional request args.
	 *
	 * @param array $args
	 * @return object
	 */
	function setArgs(array $args = []): self;

	/**
	 * Add request arg.
	 *
	 * @param string $arg
	 * @param mixed $value
	 * @return void
	 */
	function addArg(string $arg, $value = null);

	/**
	 * Set request headers.
	 *
	 * @param array $headers
	 * @return object
	 */
	function setHeaders(array $headers = []): self;

	/**
	 * Add request header.
	 *
	 * @param string $header
	 * @param mixed $value
	 * @return void
	 */
	function addHeader(string $header, $value = null);

	/**
	 * Set request cookies.
	 *
	 * @param array $cookies
	 * @return object
	 */
	function setCookies(array $cookies = []): self;

	/**
	 * Add request cookie.
	 *
	 * @param string $cookie
	 * @param mixed $value
	 * @return void
	 */
	function addCookie(string $cookie, $value = null);

	/**
	 * Set request body.
	 *
	 * @param array $body
	 * @return object
	 */
	function setBody(array $body = []): self;

	/**
	 * Add request body.
	 *
	 * @param string $body
	 * @param mixed $value
	 * @return void
	 */
	function addBody(string $body, $value = null);

	/**
	 * Send request.
	 *
	 * @param string $url
	 * @return object
	 */
	function send(?string $url = null): self;

	/**
	 * Get response body.
	 *
	 * @return string
	 */
	function body(): string;

	/**
	 * Get response status code.
	 *
	 * @return int
	 */
	function status(): int;

	/**
	 * Check response status.
	 *
	 * @param string $status
	 * @return bool
	 */
	function hasStatus(string $status): bool;

	/**
	 * Check response content.
	 *
	 * @return bool
	 */
	function hasContent(): bool;

	/**
	 * Check response item.
	 *
	 * @param string $item
	 * @param string $value
	 * @return bool
	 */
	function has(string $item, ?string $value = null): bool;

	/**
	 * Get formatted response from body.
	 *
	 * @return array
	 */
	function response(): array;

	/**
	 * Get formatted response from body (XML).
	 *
	 * @return array
	 */
	function responseXml(): array;

	/**
	 * Check response error.
	 *
	 * @return bool
	 */
	function hasError(): bool;

	/**
	 * Get response error.
	 *
	 * @return mixed
	 */
	function error();

	/**
	 * Disable SSL verification.
	 *
	 * @return object
	 */
	function noSSL(): self;

	/**
	 * Check remote server status.
	 *
	 * @param int $code
	 * @return bool
	 */
	function isDown(?int $code = null): bool;

	/**
	 * Set <Bearer> authentication.
	 *
	 * @param string $token
	 * @return void
	 */
	function setAuth(string $token);

	/**
	 * Set <Basic> authentication.
	 *
	 * @param string $user
	 * @param string $pswd
	 * @return void
	 */
	function setBasicAuth(string $user, ?string $pswd = null);

	/**
	 * Get request timeout.
	 * [Filter: {plugin}-request-timeout].
	 *
	 * @return int
	 */
	function timeout(): int;

	/**
	 * Get request user-agent.
	 * [Filter: {plugin}-request-ua].
	 *
	 * @return string
	 */
	function userAgent(): string;

	/**
	 * Filter request SSL.
	 * [Filter: http-request-args].
	 * [Filter: {plugin}-request-ssl].
	 *
	 * @param array $args
	 * @return array
	 */
	function filterSSL($args): array;

	/**
	 * Instance API.
	 *
	 * @param string $name
	 * @param string $path
	 * @param mixed $args
	 * @return mixed
	 * @throws RequestException
	 */
	static function instance(string $name, $path = 'api', ...$args);
}
