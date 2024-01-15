<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
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
	 * Init updater,
	 * [action : admin_init].
	 * 
	 * @param string $host
	 * @param array $args
	 */
	function __construct(string $host, array $args = []);

	/**
	 * Get plugin info.
	 * 
	 * @param mixed $transient
	 * @param string $action
	 * @param object $args
	 * @return mixed
	 */
	function getInfo($transient, string $action, object $args);

	/**
	 * Check plugin update.
	 * 
	 * @param mixed $transient
	 * @return object
	 */
	function checkUpdate($transient) : object;

	/**
	 * Check plugin translation update.
	 * 
	 * @param mixed $transient
	 * @return object
	 */
	function checkTranslation($transient) : object;

	/**
	 * Clear plugin updates cache,
	 * [Filter: upgrader_process_complete].
	 *
	 * @param object $upgrader
	 * @param array $options
	 * @return void
	 */
	function clearCache(object $upgrader, array $options);

	/**
	 * Filter updater args,
	 * Allow unsafe updater URLs for non SSL.
	 * 
	 * @param array $args
	 * @return array
	 */
	function filterArgs(array $args) : array;

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
