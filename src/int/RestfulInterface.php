<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
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
	 * Register REST routes.
	 * [Action: front-init].
	 *
	 * @return object
	 */
	function register() : self;

	/**
	 * Add REST routes.
	 * [Action: rest-api-init].
	 *
	 * @param object $server
	 * @return void
	 */
	function addRoutes($server);

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
	 * [Action: front-init].
	 * [Filter: rest-api-index].
	 *
	 * @param bool $grant
	 * @return object
	 */
	function noIndex(bool $grant = false) : self;

	/**
	 * Disable REST endpoints.
	 * [Action: front-init].
	 * [Filter: rest-api-endpoint].
	 *
	 * @param bool $grant
	 * @return object
	 */
	function noRoute(array $except = []) : self;

	/**
	 * Disable REST JSONP.
	 * [Action: front-init].
	 * [Filter: rest-api-jsonp].
	 *
	 * @return object
	 */
	function noPadding() : self;

	/**
	 * Override REST response.
	 * [Action: front-init].
	 * [Filter: rest-api-response].
	 *
	 * @return object
	 */
	function override() : self;

	/**
	 * Disable REST.
	 * [Action: front-init].
	 * [Filter: rest-api-error].
	 *
	 * @return void
	 */
	function disable();

	/**
	 * Restrict REST by rules.
	 * [Action: front-init].
	 * [Filter: rest-api-error].
	 *
	 * @param array $rules
	 * @return void
	 */
	function restrict(array $rules);

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
	 * Set endpoint internal access callback.
	 *
	 * @param object $request
	 * @return mixed
	 */
	function internal($request);

	/**
	 * Set REST authentication method.
	 *
	 * @param string $auth
	 * @return void
	 */
	function setAuthMethod(string $auth);
}
