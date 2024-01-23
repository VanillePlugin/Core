<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface AdminHelperInterface
{
	/**
	 * Init admin helper.
	 * 
	 * @uses initConfig()
	 */
	function __construct();

	/**
	 * Plugin activate action.
	 * [Action: {plugin}-activate].
     *
	 * @return void
	 */
	function activate();

	/**
	 * Plugin deactivate action.
	 * [Action: {plugin}-deactivate].
	 *
	 * @return void
	 */
	function deactivate();

	/**
	 * Plugin upgrade action.
	 * [Action: {plugin}-upgrade].
	 *
	 * @return void
	 */
	function upgrade();

	/**
	 * Plugin action links.
	 * [Filter: {plugin}-action].
	 * 
	 * @param array $links
	 * @return array
	 */
	function action(array $links) : array;

	/**
	 * Plugin init action.
	 * [Action: admin-init].
	 * 
	 * @return void
	 */
	function init();

	/**
	 * Plugin dashboard action.
	 * [Action: dashboard-setup].
	 * 
	 * @return void
	 */
	function dashboard();

	/**
	 * Disable auto-update.
	 * [Filter: auto-update-plugin].
	 *
	 * @param mixed $update
	 * @param object $item
	 * @return mixed
	 */
	function disableAutoUpdate($update, object $item);
}
