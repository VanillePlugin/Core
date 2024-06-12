<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\third;

use VanillePlugin\inc\{
	Hook, TypeCheck
};

/**
 * Third-Party editor helper class.
 */
final class Editor
{
	/**
	 * Check whether editor is classic.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isClassic() : bool
	{
		return TypeCheck::isClass('Classic_Editor');
	}

	/**
	 * Check whether editor is gutenberg.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isGutenberg() : bool
	{
		if ( Hook::hasFilter('replace_editor', 'gutenberg_init') ) {
			return true;
			
		} elseif ( !self::isClassic() ) {
			return true;
		}

		return false;
	}
}
