<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

/**
 * Built-in tokenizer class.
 */
class Tokenizer
{
    /**
     * Get range of numbers.
     *
     * @access public
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function range(int $min = 5, int $max = 10): int
    {
        $range = $max - $min;
        if ($range < 0) {
            return $min;
        }

        $log    = log($range, 2);
        $bytes  = (int)($log / 8) + 1;
        $bits   = (int)$log + 1;
        $filter = (1 << $bits) - 1;

        do {
            $randomBytes = (string)openssl_random_pseudo_bytes($bytes);
            $rand = hexdec(bin2hex($randomBytes));
            $rand = $rand & $filter;
        } while ($rand >= $range);

        return ($min + $rand);
    }

    /**
     * Generate random token.
     *
     * @access public
     * @param int $length
     * @param bool $special
     * @return string
     */
    public static function generate(int $length = 16, bool $special = false): string
    {
        $token  = '';
        $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chars .= 'abcdefghijklmnopqrstuvwxyz';
        $chars .= '0123456789';
        if ($special) {
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
    public static function base64(string $value, int $loop = 1): string
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
    public static function unbase64(string $value, int $loop = 1): string
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
     * @param bool $md5
     * @return string
     */
    public static function getUniqueId(bool $md5 = true): string
    {
        $id = uniqid((string)time());
        return ($md5) ? md5($id) : $id;
    }

    /**
     * Get UUID (4).
     *
     * @access public
     * @param bool $format
     * @return string
     */
    public static function getUuid(bool $format = false): string
    {
        $uuid = wp_generate_uuid4();
        if ($format) {
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
    public static function createNonce($action = -1): string
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
    public static function checkNonce(string $nonce, $action = -1): bool
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
    public static function checkAjaxNonce($action = -1, $arg = 'nonce'): bool
    {
        return (bool)check_ajax_referer($action, $arg, false);
    }

    /**
     * Hash data.
     *
     * @access public
     * @param mixed $data
     * @param string $salt
     * @return string
     */
    public static function hash($data, string $salt = 'Y3biC'): string
    {
        if (!TypeCheck::isString($data)) {
            $data = Stringify::serialize($data);
        }
        $data = "{$salt}{$data}";
        return hash('sha256', $data);
    }
}
