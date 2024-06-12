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

namespace VanillePlugin\third\inc\plugin;

/**
 * AMP plugin helper class.
 * 
 * @see https://github.com/ampproject/amp-wp/
 * @see https://github.com/ahmedkaludi/Accelerated-Mobile-Pages
 */
final class Amp
{
	/**
	 * Check whether plugin is active (functional).
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isActive() : bool
	{
		if ( function_exists('amp_is_request') ) {
			return amp_is_request();
		}
		if ( function_exists('ampforwp_is_amp_endpoint') ) {
			return ampforwp_is_amp_endpoint();
		}
		return false;
	}
}
