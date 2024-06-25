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

interface UpdaterInterface
{
	/**
	 * Init updater.
	 * [action : admin-init].
	 *
	 * @param string $host
	 * @param array $args
	 */
	function __construct(string $host, array $args = []);

	/**
	 * Get plugin info.
	 * [Filter: plugins-api].
	 *
	 * @param mixed $transient
	 * @param string $action
	 * @param object $args
	 * @return mixed
	 */
	function getInfo($transient, string $action, object $args);

	/**
	 * Check plugin update.
	 * [Filter: pre-transient-update-{$transient}].
	 *
	 * @param mixed $transient
	 * @return object
	 */
	function checkUpdate($transient) : object;

	/**
	 * Check plugin translation update.
	 * [Filter: pre-transient-update-{$transient}].
	 *
	 * @param mixed $transient
	 * @return object
	 */
	function checkTranslation($transient) : object;

	/**
	 * Filter updater request.
	 * [Filter: http-request-args].
	 *
	 * @param array $args
	 * @return array
	 */
	function filterRequest(array $args) : array;
	
	/**
	 * Clear plugin updates cache.
	 * [Action: upgrader-process-complete].
	 *
	 * @param object $upgrader
	 * @param array $options
	 * @return void
	 */
	function clearCache(object $upgrader, array $options);

	/**
	 * Get update status.
	 *
	 * @return bool
	 */
	function isUpdated() : bool;

	/**
	 * Set updated status.
	 *
	 * @return bool
	 */
	function setAsUpdated() : bool;
}
