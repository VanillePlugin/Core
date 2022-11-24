<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.3
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\thirdparty;

/**
 * Third-Party editor helper class.
 */
final class Editor
{
	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isClassic()
	{
		return class_exists('Classic_Editor');
	}

	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isGutenberg()
	{
		if ( has_filter('replace_editor','gutenberg_init') ) {
			return true;
			
		} elseif ( !self::isClassic() ) {
			return true;
		}
		return false;
	}
}
