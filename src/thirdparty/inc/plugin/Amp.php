<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\thirdparty\inc\plugin;

/**
 * AMP Helper Class.
 * 
 * @see https://github.com/ampproject/amp-wp/
 */
final class Amp
{
	/**
	 * Check whether plugin is enabled.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isEnabled()
	{
		return defined('AMP__VERSION');
	}

	/**
	 * Check whether plugin is active (functional).
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isActive()
	{
		if ( function_exists('amp_is_request') ) {
			return amp_is_request();
		}
		return false;
	}
}
