<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.6
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

class Tokenizer
{
    /**
     * @access public
     * @param string $user
     * @param string $password
     * @param bool $strict
     * @return array
     */
    public static function get($user, $password)
    {
        $tokens = [];
        $secret = md5(microtime().rand());
        $encryption = new Encryption(trim("{{$user}}:{{$password}}"),$secret);
        $tokens = [
            'public' => $encryption->encrypt(),
            'secret' => $secret
        ];
        return $tokens;
    }

    /**
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
     * base64 encode
     *
     * @access public
     * @param string $data
     * @param int $loop
     * @return string
     */
    public static function base64($data = '', $loop = 1)
    {
        $encode = base64_encode($data);
        for ($i = 1; $i < $loop; $i++) {
            $encode = base64_encode($encode);
        }
        return $encode;
    }

    /**
     * base64 decode
     *
     * @access public
     * @param string $data
     * @param int $loop
     * @return string
     */
    public static function unbase64($data = '', $loop = 1)
    {
        $decode = base64_decode($data);
        for ($i = 1; $i < $loop; $i++) {
            $decode = base64_decode($decode);
        }
        return $decode;
    }

    /**
     * Get unique Id
     *
     * @access public
     * @param void
     * @return string
     */
    public static function getUniqueId()
    {
        return md5(uniqid(time()));
    }
}
