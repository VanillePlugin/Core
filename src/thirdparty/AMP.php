<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.8
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\thirdparty;

final class AMP
{
	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isActive()
	{
		if ( function_exists('amp_is_request') ) {
			return amp_is_request();
		} else {
			return false;
		}
	}
}
