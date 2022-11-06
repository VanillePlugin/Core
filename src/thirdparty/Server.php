<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.2
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\thirdparty;

/**
 * Web server helper class.
 */
final class Server
{
	/**
	 * Get used web server name.
	 * 
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function getName()
	{
		global $is_apache, $is_nginx, $is_iis7, $is_IIS;

		if ( $is_apache ) {
			return 'Apache';

		} elseif ( $is_nginx ) {
			return 'Nginx';

		} elseif ( $is_iis7 ) {
			return 'IIS 7';

		} elseif ( $is_IIS ) {
			return 'IIS';
		}

		return 'Unknown';
	}
}
