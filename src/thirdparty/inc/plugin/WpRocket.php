<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.1
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\thirdparty\inc\plugin;

/**
 * WP Rocket Helper Class.
 * 
 * @see https://github.com/wp-media/
 */
final class WpRocket
{
	/**
	 * Check whether plugin is enabled.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isEnabled()
	{
		return defined('WP_ROCKET_VERSION');
	}

	/**
	 * Purge cache.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 * @internal
	 */
	public static function purge()
	{
		if ( function_exists('rocket_clean_domain') ) {
			rocket_clean_domain();
			return true;
		}
		return false;
	}

	/**
	 * Reset rewrite & configuration.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function reset()
	{
		// Update rewrite
		self::updateRewrite();

		// Regenerate configuration file
		self::regenerateConfiguration();
	}

	/**
	 * Update rewrite (htaccess).
	 * 
	 * @access public
	 * @param void
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
	 * @param void
	 * @return void
	 */
	public static function regenerateConfiguration()
	{
		if ( function_exists('rocket_generate_config_file') ) {
			rocket_generate_config_file();
		}
	}

	/**
	 * Force enabling geolocation.
	 * Action: activation
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function enableGeo()
	{
		if ( function_exists('flush_wp_rocket') ) {
			add_filter('rocket_cache_dynamic_cookies',function($cookies){
				return $cookies;
			});

		}
	}

	/**
	 * Force disabling geolocation.
	 * Action: deactivation
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function disableGeo()
	{
		if ( function_exists('flush_wp_rocket') ) {
			add_filter('rocket_cache_dynamic_cookies',function($cookies){
				return $cookies;
			});


		}
	}

	/**
	 * Add dynamic cookies.
	 * Filter: rocket_cache_dynamic_cookies
	 * 
	 * @access public
	 * @param array $cookies
	 * @return array
	 */
	public static function addDynamicCookies($cookies)
	{
		$cookies[] = '--country';
		return $cookies;
	}

	/**
	 * Set mandatory cookies.
	 * Filter: rocket_cache_mandatory_cookies
	 * 
	 * @access public
	 * @param array $cookies
	 * @return array
	 */
	public static function setMandatoryCookies($cookies)
	{
		$cookies[] = '--country';
		return $cookies;
	}

	/**
	 * Disable plugin rewrite.
	 * Filter: rocket_htaccess_mod_rewrite
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function disableRewrite()
	{
		add_filter('rocket_htaccess_mod_rewrite','__return_false');
	}
}
