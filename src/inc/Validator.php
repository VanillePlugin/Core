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

namespace VanillePlugin\inc;

/**
 * Advanced custom I/O validation helper,
 * @see https://wordpress.org/about/security/.
 */
class Validator
{
    /**
     * Validate IP address.
     *
	 * @access public
	 * @param string $ip
	 * @return mixed
	 */
	public static function isValidIP($ip = '')
	{
		return rest_is_ip_address($ip);
	}

    /**
     * Validate mime type.
     *
	 * @access public
	 * @param string $filename
	 * @param array $mimes
	 * @return bool
	 */
	public static function isValidMime($filename = '', $mimes = null)
	{
		$data = wp_check_filetype($filename,$mimes);
		if ( $data['ext'] && $data['type'] ) {
			return true;
		}
		return false;
	}

    /**
     * Validate email.
     *
	 * @access public
	 * @param string $email
	 * @return bool
	 */
	public static function isValidEmail($email = '')
	{
		return (bool)is_email($email);
	}

    /**
     * Validate MAC address.
     *
     * @access public
     * @param string $address
     * @return bool
     */
    public static function isValidMAC($address = '')
    {
        return (bool)Stringify::match(
        	"/^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$/i",
        	$address
        );
    }

    /**
     * Validate Url.
     *
     * @access public
     * @param string $url
     * @return bool
     */
    public static function isValidUrl($url = '')
    {
        return (bool)wp_http_validate_url($url);
    }
}
