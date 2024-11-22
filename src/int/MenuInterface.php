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

interface MenuInterface
{
	/**
	 * Construct plugin and include page settings.
	 * [Action: admin_menu].
	 *
	 * @return void
	 */
	function init();

	/**
	 * Add menu to admin bar.
	 * [Action: admin_bar_menu].
	 *
	 * @param object $bar
	 * @return void
	 */
	function bar(object $bar);

	/**
	 * Add help menu.
	 * [Action: load-{page}].
	 * 
	 * @return void
	 */
	function help();
}
