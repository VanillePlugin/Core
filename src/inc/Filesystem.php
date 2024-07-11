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

namespace VanillePlugin\inc;

/**
 * Filesystem wrapper class.
 */
class Filesystem
{
	/**
	 * Init WP filesystem.
	 * [Action: admin-init].
	 * [Action: loaded].
	 *
	 * @access public
	 * @return mixed
	 */
	public static function init()
	{
		if ( TypeCheck::isFunction('WP_Filesystem') ) {
			WP_Filesystem();
		}
	}
}
