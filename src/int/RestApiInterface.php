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

namespace VanillePlugin\int;

interface RestApiInterface
{
	/**
	 * Add routes.
	 *
	 * @param WP_REST_Server $server
	 * @return void
	 */
	function addRoutes(\WP_REST_Server $server);

	/**
	 * Init REST tokens.
	 *
	 * @return void
	 */
	function initTokens();
	
	/**
	 * Add public key.
	 *
	 * @param int $user
	 * @param string $public
	 * @return void
	 */
	function addPublicKey(int $user, string $public);

	/**
	 * Update public key.
	 *
	 * @param int $user
	 * @param string $public
	 * @return void
	 */
	function updatePublicKey(int $user, string $public);

	/**
	 * Delete public key.
	 *
	 * @param int $user
	 * @return void
	 */
	function deletePublicKey(int $user);

	/**
	 * Register REST route.
	 *
	 * @param string $endpoint
	 * @param string $route
	 * @param array $args
	 * @param bool $override
	 * @return bool
	 */
	static function registerRoute(string $endpoint, string $route, array $args, bool $override) : bool;
}
