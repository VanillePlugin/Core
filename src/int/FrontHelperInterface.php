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

interface FrontHelperInterface
{
	/**
	 * Trigger front helper hooks.
	 */
	function __construct();

	/**
	 * Plugin front load.
	 * [Action: {plugin}-load].
	 *
	 * @return void
	 */
	function load();

	/**
	 * Front loaded (Core, Plugins, Themes).
	 * [Action: loaded].
	 *
	 * @return void
	 */
	function loaded();

	/**
	 * Front setup (Core).
	 * [Action: setup].
	 *
	 * @return void
	 */
	function setup();

	/**
	 * Front AMP.
	 * [Action: amp-css].
	 * [Action: amp-head].
	 *
	 * @return void
	 */
    function amp();

	/**
	 * Front head.
	 * [Action: head].
	 *
	 * @return void
	 */
	function head();

	/**
	 * Front init.
	 * [Action: front-init].
	 *
	 * @return void
	 */
	function init();

	/**
	 * Front template (Redirect).
	 * [Action: template].
	 * [Action: template-redirect].
	 *
	 * @return void
	 */
	function template();

	/**
	 * Plugin front data (JS).
	 * [Filter: {plugin}-front-data].
	 *
	 * @param array $data
	 * @return array
	 */
	function frontData(array $data) : array;

	/**
	 * Plugin login data (JS).
	 * [Filter: {plugin}-login-data].
	 *
	 * @param array $data
	 * @return array
	 */
	function loginData(array $data) : array;
}
