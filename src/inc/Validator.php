<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.0
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
	 * @return bool
	 */
	public static function isValidIP($ip)
	{
		if ( rest_is_ip_address($ip) ) {
			return true;
		}
		return false;
	}

	/**
	 * @access public
	 * @param string $filename
	 * @param array $mimes
	 * @return bool
	 */
	public static function isValidMime($filename, $mimes = null)
	{
		$data = File::getMime($filename,$mimes);
		if ( $data['ext'] && $data['type'] ) {
			return true;
		}
		return false;
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

    /**
     * Validate MAC address.
     *
     * @access public
     * @param string $address
     * @return bool
     */
    public static function isValidMac($address)
    {
        return (bool)Stringify::match("/^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$/i",$address);
    }
}
