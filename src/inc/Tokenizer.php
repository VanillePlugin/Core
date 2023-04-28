<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
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
     * @param string $username
     * @param string $password
     * @param string $prefix
     * @return array
     */
    public static function get($username, $password, $prefix = '')
    {
        $secret = self::getUniqueId();
        $encryption = new Encryption("{user:{$username}}{pswd:{$password}}",$secret);
        $encryption->setPrefix($prefix);
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
    public static function match($public, $secret, $prefix = '')
    {
        $pattern = '/{user:(.*?)}{pswd:(.*?)}/';
        $encryption = new Encryption($public,$secret);
        $encryption->setPrefix($prefix);
        $access = $encryption->decrypt();
        $username = Stringify::match($pattern,$access,1);
        $password = Stringify::match($pattern,$access,2);
        if ( $username && $password ) {
            return [
                'username' => $username,
                'password' => $password
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
    public static function range($min = 5, $max = 10)
    {
        $range = $max - $min;
        if ( $range < 0 ) {
            return $min;
        }
        $log = log($range,2);
        $bytes = (int) ($log / 8) + 1;
        $bits = (int) $log + 1;
        $filter = (1 << $bits) - 1;
        do {
            $randomBytes = (string) openssl_random_pseudo_bytes($bytes);
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
     * @param string $seeds
     * @return string
     */
    public static function generate($length = 32, $special = false, $seeds = '')
    {
        $token = '';
        if ( empty($seeds) ) {
            $seeds  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $seeds .= 'abcdefghijklmnopqrstuvwxyz';
            $seeds .= '0123456789';
            if ( $special ) {
                $seeds .= '!#$%&()*+,-.:;<>?@[]^{}~';
            }
        }
        for ($i = 0; $i < $length; $i++) {
            $token .= $seeds[self::range(0,strlen($seeds))];
        }
        return $token;
    }

    /**
     * base64 encode.
     *
     * @access public
     * @param string $data
     * @param int $loop
     * @return string
     */
    public static function base64($data = '', $loop = 1)
    {
        $encode = base64_encode($data);
        $loop = ($loop > 10) ?? 10;
        for ($i = 1; $i < $loop; $i++) {
            $encode = base64_encode($encode);
        }
        return $encode;
    }

    /**
     * base64 decode.
     *
     * @access public
     * @param string $data
     * @param int $loop
     * @return string
     */
    public static function unbase64($data = '', $loop = 1)
    {
        $decode = base64_decode($data);
        $loop = ($loop > 10) ?? 10;
        for ($i = 1; $i < $loop; $i++) {
            $decode = base64_decode($decode);
        }
        return $decode;
    }

    /**
     * Get random unique Id.
     *
     * @access public
     * @param void
     * @return string
     */
    public static function getUniqueId()
    {
        return md5(
            uniqid((string)time())
        );
    }

    /**
     * Get random UUID (4).
     *
     * @access public
     * @param bool $format
     * @return string
     */
    public static function getUUID($format = false)
    {
        $uuid = wp_generate_uuid4();
        if ( $format ) {
            return Stringify::replace('-','',$uuid);
        }
        return $uuid;
    }
}
