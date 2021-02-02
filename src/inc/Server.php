<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.5
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

class Server
{
	/**
	 * @access public
	 * @param string $item null
	 * @return mixed
	 */
	public static function get($item = null)
	{
		if ($item) {
			return self::isSetted($item) ? $_SERVER[$item] : false;
		} else {
			return $_SERVER;
		}
	}

	/**
	 * @access public
	 * @param string $item
	 * @param mixed $value
	 * @return void
	 */
	public static function set($item, $value)
	{
		$_SERVER[$item] = $value;
	}

	/**
	 * @access public
	 * @param string $item null
	 * @return boolean
	 */
	public static function isSetted($item = null)
	{
		if ($item) {
			return isset($_SERVER[$item]);
		} else {
			return isset($_SERVER);
		}
	}

	/**
	 * @access public
	 * @param void
	 * @return boolean
	 */
	public static function isHttps()
	{
		if ( self::isSetted('HTTPS') 
			&& !empty(self::get('HTTPS')) 
			&& self::get('HTTPS') !== 'off' ) {
		    return true;
		}
		return false;
	}
	
	/**
	 * Get current user IP Address
	 *
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function getRemote()
	{
		if ( self::isSetted('HTTP_X_REAL_IP') ) {
			$ip = self::get('HTTP_X_REAL_IP');
			return Stringify::sanitizeText(Stringify::slashStrip($ip));

		} elseif ( self::isSetted('HTTP_X_FORWARDED_FOR') ) {
			$ip = self::get('HTTP_X_FORWARDED_FOR');
			$ip = Stringify::sanitizeText(Stringify::slashStrip($ip));
			$ip = Stringify::split($ip, ['regex' => '/,/']);
 			return (string) Validator::isValidIP(trim(current($ip)));

		} elseif ( self::isSetted('REMOTE_ADDR') ) {
			$ip = self::get('REMOTE_ADDR');
			return Stringify::sanitizeText(Stringify::slashStrip($ip));
		}
		return false;
	}

	/**
	 * Get prefered protocol
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function getProtocol()
	{
		return Server::isHttps() ? 'https://' : 'http://';
	}

	/**
	 * Get country code from request headers
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function getCountryCode()
	{
		$headers = [
			'MM_COUNTRY_CODE',
			'GEOIP_COUNTRY_CODE',
			'HTTP_CF_IPCOUNTRY',
			'HTTP_X_COUNTRY_CODE'
		];
		foreach ($headers as $header) {
			if ( self::isSetted($header) ) {
				$code = self::get($header);
				if ( !empty($code) ) {
					$code = Stringify::sanitizeText(Stringify::slashStrip($code));
					return Stringify::uppercase($code);
					break;
				}
			}
		}
		return false;
	}
}
