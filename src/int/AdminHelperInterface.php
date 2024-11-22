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

interface AdminHelperInterface
{
	/**
	 * Trigger admin helper hooks.
	 */
	function __construct();

	/**
	 * Plugin activate.
	 * [Action: {plugin}-activate].
     *
	 * @return void
	 */
	function activate();

	/**
	 * Plugin deactivate.
	 * [Action: {plugin}-deactivate].
	 *
	 * @return void
	 */
	function deactivate();

	/**
	 * Plugin upgrade.
	 * [Action: {plugin}-upgrade].
	 *
	 * @return void
	 */
	function upgrade();

	/**
	 * Plugin admin load.
	 * [Action: {plugin}-load].
	 *
	 * @return void
	 */
	function load();

	/**
	 * Plugin admin scripts.
	 * [Action: {plugin}-admin-script].
	 *
	 * @return void
	 */
	function adminScript();

	/**
	 * Plugin global scripts.
	 * [Action: {plugin}-global-script].
	 *
	 * @return void
	 */
	function globalScript();

	/**
	 * Admin loaded (Core, Plugins, Themes).
	 * [Action: loaded].
	 *
	 * @return void
	 */
	function loaded();

	/**
	 * Admin init.
	 * [Action: admin-init].
	 * 
	 * @return void
	 */
	function init();

	/**
	 * Admin dashboard.
	 * [Action: dashboard-setup].
	 * 
	 * @return void
	 */
	function dashboard();

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
	 * [Filter: {plugin}-about-text].
	 * 
	 * @param string $output
	 * @return string
	 */
	function about(string $output) : string;

	/**
	 * Plugin version.
	 * [Filter: {plugin}-about-version].
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
	 * Plugin auto-update.
	 * [Filter: auto-update-plugin].
	 * [Filter: {plugin}-disable-auto-update].
	 *
	 * @param mixed $update
	 * @param object $item
	 * @return mixed
	 */
	function autoUpdate($update, object $item);
}
