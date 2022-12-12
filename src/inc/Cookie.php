<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.3
 * @copyright : (c) 2018 - 2023 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public static function get($key = null)
	{
        if ( $key ) {
            return self::isSetted($key) ? $_COOKIE[$key] : null;
        }
        return self::isSetted() ? $_COOKIE : null;
	}

	/**
	 * @access public
	 * @param string $key
	 * @param string $value
	 * @param array $options
	 * @return bool
	 */
	public static function set($key, $value = '', $options = [])
	{
		return setcookie($key,$value,$options);
	}
	
	/**
	 * @access public
	 * @param string $key
	 * @return bool
	 */
	public static function isSetted($key = null)
	{
        if ( $key ) {
            return isset($_COOKIE[$key]);
        }
        return isset($_COOKIE) && !empty($_COOKIE);
	}

	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function clear()
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
