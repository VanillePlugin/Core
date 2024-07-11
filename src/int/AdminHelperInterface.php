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

interface AdminHelperInterface
{
	/**
	 * Init admin helper.
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
	 * Plugin load action.
	 * [Action: {plugin}-load].
	 *
	 * @return void
	 */
	function load();

	/**
	 * Plugin admin loaded action.
	 * [Action: {plugin}-admin-loaded].
	 *
	 * @return void
	 */
	function loaded();

	/**
	 * Plugin action links.
	 * [Filter: {plugin}-action].
	 * 
	 * @param array $links
	 * @return array
	 */
	function action(array $links) : array;

	/**
	 * Plugin about.
	 * [Filter: {plugin}-about].
	 * 
	 * @param string $output
	 * @return string
	 */
	function about(string $output) : string;

	/**
	 * Plugin version.
	 * [Filter: {plugin}-version].
	 * 
	 * @param string $output
	 * @return string
	 */
	function version(string $output) : string;

	/**
	 * Plugin row.
	 * [Filter: plugin-row].
	 * 
	 * @param array $meta
	 * @param string $file
	 * @return array
	 */
	function row(array $meta, string $file) : array;

	/**
	 * Plugin admin data (JS).
	 * [Filter: {plugin}-admin-data].
	 *
	 * @param array $data
	 * @return array
	 */
	function adminData(array $data) : array;

	/**
	 * Plugin global data (JS).
	 * [Filter: {plugin}-global-data].
	 *
	 * @param array $data
	 * @return array
	 */
	function globalData(array $data) : array;

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
	 * Disable plugin auto-update.
	 * [Filter: auto-update-plugin].
	 *
	 * @param mixed $update
	 * @param object $item
	 * @return mixed
	 */
	function disableAutoUpdate($update, object $item);
}
