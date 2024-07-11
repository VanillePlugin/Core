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

namespace VanillePlugin\int;

interface RestfulInterface
{
	/**
	 * Set REST namepsace.
	 *
	 * @param string $namespace
	 * @param string $version
	 */
	function __construct(?string $namespace = null, ?string $version = null);

	/**
	 * Init REST API.
	 * [Action: init].
	 * [Action: admin-init].
	 *
	 * @return object
	 */
	function init() : self;

	/**
	 * Set REST prefix.
	 * [Action: {plugin}-load].
	 * [Filter: rest-api-prefix].
	 *
	 * @param string $prefix
	 * @return object
	 */
	function prefix(string $prefix) : self;

	/**
	 * Disable REST index.
	 * [Action: init].
	 * [Filter: rest-api-index].
	 *
	 * @param bool $grant
	 * @return object
	 */
	function noIndex(bool $grant = false) : self;

	/**
	 * Disable REST endpoints.
	 * [Action: init].
	 * [Filter: rest-api-endpoint].
	 *
	 * @param bool $grant
	 * @return object
	 */
	function noRoute(array $except = []) : self;

	/**
	 * Disable REST JSONP.
	 * [Action: init].
	 * [Filter: rest-api-jsonp].
	 *
	 * @return object
	 */
	function noPadding() : self;

	/**
	 * Override REST 404.
	 * [Action: init].
	 * [Filter: rest-api-response].
	 *
	 * @return object
	 */
	function notFound() : self;

	/**
	 * Disable REST.
	 * [Action: init].
	 * [Filter: rest-api-error].
	 *
	 * @return void
	 */
	function disable();

	/**
	 * Restrict REST by rules.
	 * [Action: init].
	 * [Filter: rest-api-error].
	 *
	 * @param array $rules
	 * @return void
	 */
	function restrict(array $rules);
	
	/**
	 * Add REST routes.
	 * [Action: rest-api-init].
	 *
	 * @param $server
	 * @return void
	 */
	function addRoutes($server);

	/**
	 * Set endpoint default action callback.
	 *
	 * @param object $request
	 * @return mixed
	 */
	function action($request);

	/**
	 * Set endpoint default access callback.
	 *
	 * @param object $request
	 * @return mixed
	 */
	function access($request);
	
	/**
	 * Add auth token.
	 *
	 * @param int $user
	 * @param string $token
	 * @return bool
	 */
	function addToken(int $user, string $token) : bool;

	/**
	 * Update auth token.
	 *
	 * @param int $user
	 * @param string $token
	 * @return bool
	 */
	function updateToken(int $user, string $token) : bool;

	/**
	 * Delete auth token.
	 *
	 * @param int $user
	 * @return bool
	 */
	function deleteToken(int $user) : bool;
}
