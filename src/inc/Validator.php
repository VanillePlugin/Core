<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

/**
 * Advanced custom I/O validation helper,
 * @see https://wordpress.org/about/security/.
 */
class Validator
{
    /**
     * Validate email.
     *
     * @access public
     * @param mixed $email
     * @return bool
     */
    public static function isValidEmail($email) : bool
    {
        return (bool)is_email($email);
    }

    /**
     * Validate URL.
     *
     * @access public
     * @param mixed $url
     * @return bool
     */
    public static function isValidUrl($url) : bool
    {
        return (bool)wp_http_validate_url($url);
    }

    /**
     * Validate date.
     *
     * @access public
     * @param mixed $date
     * @param bool $time
     * @return bool
     */
    public static function isValidDate($date, bool $time = false) : bool
    {
		// Object
		if ( TypeCheck::isObject($date) ) {
			return ($date instanceof \DateTime);
		}

		// String
		$date = Stringify::lowercase((string)$date);
		if ( $date == 'now' ) {
			return true;
		}

		// Regex
		$pattern = '/^(\d{4})[-,\/](\d{2})[-,\/](\d{2})$/';
		if ( $time ) {
			$pattern = '/(\d{2,4})[-,\/](\d{2})[-,\/](\d{2,4})[ ,T](\d{2})/i';
		}

		return (bool)Stringify::match($pattern, $date);
    }

    /**
     * Validate IP.
     *
	 * @access public
	 * @param string $ip
	 * @return bool
	 */
	public static function isValidIp(string $ip) : bool
	{
		return (bool)rest_is_ip_address($ip);
	}

    /**
     * Validate mime type.
     *
	 * @access public
	 * @param string $filename
	 * @param array $mimes
	 * @return bool
	 */
	public static function isValidMime(string $filename, ?array $mimes = null) : bool
	{
		$data = wp_check_filetype($filename, $mimes);
		if ( $data['ext'] && $data['type'] ) {
			return true;
		}
		return false;
	}

    /**
     * Validate MAC.
     *
     * @access public
     * @param string $address
     * @return bool
     */
    public static function isValidMac(string $address) : bool
    {
        return (bool)Stringify::match(
        	"/^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$/i",
        	$address
        );
    }

	/**
	 * Validate PHP module.
	 *
	 * @access public
	 * @param string $extension
	 * @return bool
	 */
	public static function isModule(string $extension) : bool
	{
		return extension_loaded($extension);
	}

	/**
	 * Validate server config.
	 * 
	 * @access public
	 * @param string $name
	 * @param mixed $value
	 * @return bool
	 */
	public static function isConfig(string $name, $value) : bool
	{
		return (System::getIni($name) == $value);
	}
	
	/**
	 * Validate version.
	 *
	 * @access public
	 * @param string $v1
	 * @param string $v2
	 * @param string $operator
	 * @return bool
	 */
	public static function isVersion(string $v1, string $v2, string $operator = '==') : bool
	{
		return version_compare($v1, $v2, $operator);
	}

	/**
	 * Validate plugin file,
	 * [{pluginDir}/{pluginMain}.php].
	 *
	 * @access public
	 * @param string $file
	 * @return bool
	 */
	public static function isPlugin(string $file) : bool
	{
		if ( function_exists('is_plugin_active') ) {
			return is_plugin_active($file);
		}
		$plugins = apply_filters('active_plugins', get_option('active_plugins'));
		return Arrayify::inArray($file, $plugins);
	}

	/**
	 * Validate plugin class.
	 *
	 * @access public
	 * @param string $callable
	 * @return bool
	 */
	public static function isPluginClass(string $callable) : bool
	{
		$callable = Stringify::replace('/', '\\', $callable);
		if ( Stringify::contains($callable, '\\') ) {
			$file = Plugin::getDir("{$callable}.php");
			if ( !File::exists($file) ) {
				return false;
			}
		}
		return TypeCheck::isClass($callable);
	}

	/**
	 * Validate plugin version,
	 * [{pluginDir}/{pluginMain}.php].
	 *
	 * @access public
	 * @param string $file
	 * @param string $version
	 * @param string $operator
	 * @return bool
	 */
	public static function isPluginVersion(string $file, string $version, string $operator = '>=') : bool
	{
		if ( self::isPlugin($file) ) {
			$data = Plugin::getData($file);
			return self::isVersion($data['version'], $version, $operator);
		}
		return false;
	}
}
