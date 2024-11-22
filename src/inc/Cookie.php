<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

final class Cookie
{
	/**
	 * Get _COOKIE value.
	 * 
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public static function get(?string $key = null)
	{
        if ( $key ) {
            return self::isSetted($key) ? $_COOKIE[$key] : null;
        }
        return self::isSetted() ? $_COOKIE : null;
	}

	/**
	 * Set _COOKIE value.
	 * 
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param mixed $options
	 * @return bool
	 */
	public static function set(string $key, $value = '', $options = [])
	{
		return setcookie($key, $value, $options);
	}
	
	/**
	 * Check _COOKIE value.
	 * 
	 * @access public
	 * @param string $key
	 * @return bool
	 */
	public static function isSetted(?string $key = null) : bool
	{
        if ( $key ) {
            return isset($_COOKIE[$key]);
        }
        return isset($_COOKIE) && !empty($_COOKIE);
	}
	
	/**
	 * Unset _COOKIE value.
	 * 
	 * @access public
	 * @param string $key
	 * @return void
	 */
	public static function unset(?string $key = null)
	{
		if ( $key ) {
			unset($_COOKIE[$key]);

		} else {
			$_COOKIE = [];
		}
	}

	/**
	 * Clear session cookie.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function clear() : bool
	{
        if ( System::getIni('session.use_cookies') ) {
            $params = session_get_cookie_params();
            self::set(Session::getName(), '', [
            	'expires'  => time() - 42000,
            	'path'     => $params['path'],
            	'domain'   => $params['domain'],
            	'secure'   => $params['secure'],
            	'httponly' => $params['httponly'],
            	'samesite' => $params['samesite']
            ]);
            return true;
        }
        return false;
	}
}
