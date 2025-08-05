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

interface UpdaterInterface
{
	/**
	 * Init updater.
	 * [Action : admin-init].
	 *
	 * @param array $auth
	 * @param array $url
	 */
	function __construct(array $auth = [], array $url = []);

	/**
	 * Set update listener.
	 *
	 * @return void
	 */
	function listen();

	/**
	 * Set updater host.
	 *
	 * @param mixed $host
	 * @return bool
	 */
	function setHost($host): bool;

	/**
	 * Get update status.
	 *
	 * @return bool
	 */
	function isUpdated(): bool;

	/**
	 * Set updated status.
	 *
	 * @return bool
	 */
	function setAsUpdated(): bool;

	/**
	 * Remove plugin updates.
	 *
	 * @return bool
	 */
	function remove(): bool;

	/**
	 * Get plugin info.
	 * [Filter: plugins-api].
	 *
	 * @param mixed $transient
	 * @param string $action
	 * @param object $args
	 * @return mixed
	 */
	function getInfo($transient, $action, $args);

	/**
	 * Check plugin core update.
	 * [Filter: update-plugins].
	 *
	 * @param mixed $transient
	 * @return object
	 */
	function checkUpdate($transient): object;

	/**
	 * Check plugin translation update.
	 * [Filter: update-plugins].
	 *
	 * @param mixed $transient
	 * @return object
	 */
	function checkTranslation($transient): object;

	/**
	 * Clear plugin update cache.
	 * [Action: upgrade-complete].
	 *
	 * @param object $upgrader
	 * @param array $options
	 * @return void
	 */
	function clearCache($upgrader, $options);

	/**
	 * Set updater TTL.
	 * [Filter: {plugin}-updater-ttl].
	 *
	 * @param int $ttl
	 * @param string $action
	 * @return int
	 */
	function ttl($ttl, $action): int;

	/**
	 * Get updater timeout.
	 * [Filter: {plugin}-updater-timeout].
	 *
	 * @return int
	 */
	function timeout(): int;
}
