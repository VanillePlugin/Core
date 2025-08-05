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

interface GlobalHelperInterface
{
	/**
	 * Trigger global helper hooks.
	 */
	function __construct();

	/**
	 * Plugin global load.
	 * [Action: {plugin}-load].
	 *
	 * @return void
	 */
	function load();

	/**
	 * Global loaded (Core, Plugins, Themes).
	 * [Action: loaded].
	 *
	 * @return void
	 */
	function loaded();

	/**
	 * Global init.
	 * [Action: init].
	 *
	 * @return void
	 */
	function init();
}
