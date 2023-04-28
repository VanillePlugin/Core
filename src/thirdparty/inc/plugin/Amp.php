<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\thirdparty\inc\plugin;

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
	 * @param void
	 * @return bool
	 */
	public static function isActive()
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
