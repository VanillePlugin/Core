<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.5.0
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Server
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
	 * @return bool
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
	 * @return bool
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
	 * @access private
	 * @param void
	 * @return mixed
	 */
	public static function getAuthorizationHeaders()
	{
        $headers = null;
        if ( self::isSetted('Authorization') ) {
            $headers = trim(self::get('Authorization'));

        } elseif ( self::isSetted('HTTP_AUTHORIZATION') ) {
            $headers = trim(self::get('HTTP_AUTHORIZATION'));

        } elseif ( function_exists('apache_request_headers') ) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(
            	array_map('ucwords',array_keys($requestHeaders)),array_values($requestHeaders)
            );
            if ( isset($requestHeaders['Authorization']) ) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
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

    /**
     * @access public
     * @param void
     * @return bool
     */
    public static function isApache()
    {
        if ( function_exists('apache_get_version') ) {
            $apache = apache_get_version();
            $apache = Stringify::replace('Apache/','',$apache);
            $apache = substr($apache,0,3);
            return (floatval($apache) >= 2.4) ? true : false;
        }
        return false;
    }
}
