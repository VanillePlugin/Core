<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

final class Password
{
    /**
     * Generate password.
     *
     * @access public
     * @param int $length
     * @param bool $special
     * @param bool $extra
     * @return string
     */
    public static function generate(int $length = 8, bool $special = true, bool $extra = false) : string
    {
        return wp_generate_password($length, $special, $extra);
    }

    /**
     * Check whether password is valid against hash.
     * 
     * @access public
     * @param string $pswd
     * @param string $hash
     * @param mixed $user
     * @return bool
     */
    public static function isValid(string $pswd, string $hash, $user = null) : bool
    {
        return wp_check_password($pswd, $hash, (int)$user);
    }

    /**
     * Hash password.
     * 
     * @access public
     * @param string $pswd
     * @return string
     */
    public static function hash(string $pswd) : string
    {
        return wp_hash_password($pswd);
    }

	/**
     * Send password.
     * 
	 * @access public
	 * @param string $login
	 * @return bool
	 */
	public static function send(?string $login = null) : bool
	{
		$send = retrieve_password($login);
		if ( !Exception::isError($send) ) {
			return true;
		}
		return false;
	}

    /**
     * Check password is strong.
     * 
     * @access public
     * @param string $pswd
     * @param int $length
     * @return bool
     */
    public static function isStrong(string $pswd, int $length = 8) : bool
    {
        if ( $length < 8 ) {
            $length = 8;
        }
        
        $uppercase = Stringify::match('@[A-Z]@', $pswd);
        $lowercase = Stringify::match('@[a-z]@', $pswd);
        $number    = Stringify::match('@[0-9]@', $pswd);
        $special   = Stringify::match('@[^\w]@', $pswd);

        if ( !$uppercase 
          || !$lowercase 
          || !$number 
          || !$special 
          || strlen($pswd) < $length 
        ) {
            return false;
        }
        return true;
    }
}
