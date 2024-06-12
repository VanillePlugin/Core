<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

/**
 * Built-in tokenizer class,
 * @see JWT for external use is recommended.
 */
class Tokenizer
{
    /**
     * Get token.
     * 
     * @access public
     * @param string $user
     * @param string $pswd
     * @param string $prefix
     * @return array
     */
    public static function get(string $user, string $pswd, ?string $prefix = null) : array
    {
        $secret = self::getUniqueId();
        $token = trim("{user:{$user}}{pswd:{$pswd}}");
        $encryption = new Encryption($token, $secret);
        $encryption->setPrefix((string)$prefix);
        return [
            'public' => $encryption->encrypt(),
            'secret' => $secret
        ];
    }

    /**
     * Match token.
     * 
     * @access public
     * @param string $public
     * @param string $secret
     * @param string $prefix
     * @return mixed
     */
    public static function match(string $public, string $secret, ?string $prefix = null)
    {
        $pattern = '/{user:(.*?)}{pswd:(.*?)}/';
        $encryption = new Encryption($public, $secret);
        $encryption->setPrefix((string)$prefix);
        $access = $encryption->decrypt();
        $user = Stringify::match($pattern, $access, 1);
        $pswd = Stringify::match($pattern, $access, 2);
        if ( $user && $pswd ) {
            return [
                'username' => $user,
                'password' => $pswd
            ];
        }
        return false;
    }

    /**
     * Get range of numbers.
     * 
     * @access public
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function range(int $min = 5, int $max = 10) : int
    {
        $range = $max - $min;
        if ( $range < 0 ) {
            return $min;
        }
        $log = log($range, 2);
        $bytes = (int)($log / 8) + 1;
        $bits = (int)$log + 1;
        $filter = (1 << $bits) - 1;
        do {
            $randomBytes = (string)openssl_random_pseudo_bytes($bytes);
            $rand = hexdec(bin2hex($randomBytes));
            $rand = $rand & $filter;
        } while ($rand >= $range);
        return $min + $rand;
    }

    /**
     * Generate token.
     * 
     * @access public
     * @param int $length
     * @param bool $special
     * @return string
     */
    public static function generate(int $length = 16, bool $special = false) : string
    {
        $token = '';
        $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chars .= 'abcdefghijklmnopqrstuvwxyz';
        $chars .= '0123456789';
        if ( $special ) {
            $chars .= '!#$%&()*+,-.:;<>?@[]^{}~';
        }
        for ($i = 0; $i < $length; $i++) {
            $token .= $chars[self::range(0, strlen($chars))];
        }
        return $token;
    }

    /**
     * Encode base64.
     *
     * @access public
     * @param string $value
     * @param int $loop
     * @return string
     */
    public static function base64(string $value, int $loop = 1) : string
    {
        $encode = base64_encode($value);
        $loop = ($loop > 5) ? 5 : $loop;
        for ($i = 1; $i < $loop; $i++) {
            $encode = base64_encode($encode);
        }
        return $encode;
    }

    /**
     * Decode base64.
     *
     * @access public
     * @param string $value
     * @param int $loop
     * @return string
     */
    public static function unbase64(string $value, int $loop = 1) : string
    {
        $decode = base64_decode($value);
        $loop = ($loop > 5) ? 5 : $loop;
        for ($i = 1; $i < $loop; $i++) {
            $decode = base64_decode($decode);
        }
        return $decode;
    }

    /**
     * Get unique Id.
     *
     * @access public
     * @return string
     */
    public static function getUniqueId() : string
    {
        return md5(
            uniqid((string)time())
        );
    }

    /**
     * Get UUID (4).
     *
     * @access public
     * @param bool $format
     * @return string
     */
    public static function getUuid(bool $format = false) : string
    {
        $uuid = wp_generate_uuid4();
        if ( $format ) {
            return Stringify::remove('-', $uuid);
        }
        return $uuid;
    }
	/**
	 * Create nonce.
	 *
	 * @access public
	 * @param mixed $action
	 * @return string
	 */
	public static function createNonce($action = -1) : string
	{
	  	return wp_create_nonce($action);
	}

	/**
	 * Check nonce.
	 *
	 * @access public
	 * @param string $nonce
	 * @param mixed $action
	 * @return bool
	 */
	public static function checkNonce(string $nonce, $action = -1) : bool
	{
	  	return (bool)wp_verify_nonce($nonce, $action);
	}

	/**
	 * Check AJAX nonce.
	 *
	 * @access public
	 * @param mixed $action
	 * @param mixed $arg Query nonce
	 * @return bool
	 */
	public static function checkAjaxNonce($action = -1, $arg = 'nonce') : bool
	{
	  	return (bool)check_ajax_referer($action, $arg, false);
	}
}
