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

final class Password extends Tokenizer
{
    /**
     * Generate random password.
     *
     * @access public
     * @param int $length
     * @param bool $special, Special chars
     * @param bool $extra, Extra chars
     * @return string
     */
    public static function generate($length = 12, $special = true, $extra = false)
    {
        return wp_generate_password($length,$special,$extra);
    }

    /**
     * Validate password against the encrypted password.
     * 
     * @access public
     * @param string $password
     * @param string $hash
     * @param mixed $user
     * @return bool
     */
    public static function isValid($password, $hash, $user = '')
    {
        return wp_check_password($password,$hash,$user);
    }

    /**
     * Hash password.
     * 
     * @access public
     * @param string $password
     * @return string
     */
    public static function hash($password)
    {
        return wp_hash_password($password);
    }

    /**
     * Check password is strong.
     * 
     * @access public
     * @param string $password
     * @param int $length
     * @return bool
     */
    public static function isStrong($password = '', $length = 8)
    {
        if ( (int)$length < 8 ) {
            $length = 8;
        }
        
        $uppercase = Stringify::match('@[A-Z]@',$password);
        $lowercase = Stringify::match('@[a-z]@',$password);
        $number    = Stringify::match('@[0-9]@',$password);
        $special   = Stringify::match('@[^\w]@',$password);

        if ( !$uppercase 
          || !$lowercase 
          || !$number 
          || !$special 
          || strlen($password) < $length 
        ) {
            return false;
        }
        return true;
    }
}
