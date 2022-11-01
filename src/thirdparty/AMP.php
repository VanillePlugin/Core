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

namespace VanillePlugin\thirdparty;

use VanillePlugin\thirdparty\inc\plugin\Amp as WpAmp;
use VanillePlugin\thirdparty\inc\plugin\AmpForWp;

/**
 * Third-Party AMP Helper Class.
 */
final class AMP
{
	/**
	 * Check whether AMP is active (functional).
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isActive()
	{
		/**
		 * Check AMP.
		 */
		if ( WpAmp::isEnabled() ) {
			return WpAmp::isActive();
		}

		/**
		 * Check AMP for WP.
		 */
		if ( AmpForWp::isEnabled() ) {
			return AmpForWp::isActive();
		}
		
		return false;
	}
}
