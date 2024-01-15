<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\third\inc\plugin;

/**
 * WP Rocket plugin helper class.
 * 
 * @see https://github.com/wp-media/
 */
final class WpRocket
{
	public static $options = [];

	/**
	 * Check whether plugin is enabled.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return defined('WP_ROCKET_VERSION');
	}

	/**
	 * Purge cache.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function purge() : bool
	{
		if ( function_exists('rocket_clean_domain') ) {
			rocket_clean_domain();
			return true;
		}
		return false;
	}

	/**
	 * Force loading geotargeting rules through cache using cookies.
	 * 
	 * @access public
	 * @param string $name
	 * @return void
	 */
	public static function loadGeotargeting(string $name)
	{
		// Set options
		static::$options['geotargeting'] = $name;

		// Creates cache file for each value of a specified cookie
		add_filter('rocket_cache_dynamic_cookies', [new self, 'setCookies'], 80);

		// Prevents caching until the specified cookie is set
		add_filter('rocket_cache_mandatory_cookies', [new self, 'setCookies'], 80);
	}

	/**
	 * Force enabling geotargeting through cache using cookies.
	 * 
	 * @access public
	 * @param string $name
	 * @return void
	 */
	public static function enableGeotargeting(string $name)
	{
		// Set options
		static::$options['geotargeting'] = $name;

		// Creates cache file for each value of a specified cookie
		add_filter('rocket_cache_dynamic_cookies', [new self, 'setCookies'], 80);

		// Prevents caching until the specified cookie is set
		add_filter('rocket_cache_mandatory_cookies', [new self, 'setCookies'], 80);

		// Update rewrite
		self::updateRewrite();

		// Regenerate configuration file
		self::regenerateConfiguration();

		// Clear cache
		self::purge();
	}

	/**
	 * Disabling cache geotargeting.
	 * 
	 * @access public
	 * @param string $name
	 * @return void
	 */
	public static function disableGeotargeting(string $name)
	{
		// Set options
		static::$options['geotargeting'] = $name;
		
		// Remove dynamic cookies
		remove_filter('rocket_cache_dynamic_cookies', [new self, 'setCookies'], 80);

		// Remove mandatory cookies
		remove_filter('rocket_cache_mandatory_cookies', [new self, 'setCookies'], 80);

		// Update rewrite
		self::updateRewrite();

		// Regenerate configuration file
		self::regenerateConfiguration();

		// Clear cache
		self::purge();
	}

	/**
	 * Cache dynamic cookies,
	 * [Filter: rocket_cache_dynamic_cookies],
	 * [Filter: rocket_cache_mandatory_cookies].
	 * 
	 * @access public
	 * @param array $cookies
	 * @return array
	 */
	public static function setCookies(array $cookies) : array
	{
		$name = static::$options['geotargeting'] ?? '--default-country';
		$cookies[] = $name;
		return $cookies;
	}

	/**
	 * Update rewrite (htaccess).
	 * 
	 * @access public
	 * @return void
	 */
	public static function updateRewrite()
	{
		if ( function_exists('flush_rocket_htaccess') ) {
			flush_rocket_htaccess();
		}
	}

	/**
	 * Regenerate configuration (file).
	 * 
	 * @access public
	 * @return void
	 */
	public static function regenerateConfiguration()
	{
		if ( function_exists('rocket_generate_config_file')
		  && function_exists('get_rocket_cache_reject_uri') ) {
			rocket_generate_config_file();
		}
	}
}
