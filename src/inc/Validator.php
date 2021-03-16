<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.4.5
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

class Validator
{
	/**
	 * @access public
	 * @param string $ip
	 * @return mixed
	 */
	public static function isValidIP($ip)
	{
		return rest_is_ip_address($ip);
	}

	/**
	 * @access public
	 * @param string $email
	 * @return bool
	 */
	public static function isValidEmail($email)
	{
		if ( is_email($email) ) {
			return true;
		}
		return false;
	}
}
