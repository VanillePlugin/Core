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

namespace VanillePlugin\third;

use VanillePlugin\inc\GlobalConst;
use VanillePlugin\third\inc\module\{
	Opcache, Apcu
};
use VanillePlugin\third\inc\plugin\{
	Redis, WpRocket, LiteSpeed,
	WpOptimize, W3TotalCache, Kinsta,
	WpFastestCache, WpSuperCache, CacheEnabler
};

/**
 * Third-Party cache helper class.
 */
final class Cache
{
	/**
	 * Check whether cache is active (functional),
	 * Using WordPress cache constant.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isActive() : bool
	{
		if ( GlobalConst::cache() ) {
			return true;
		}
		if ( Opcache::isEnabled() ) {
			return true;
		}
		if ( Apcu::isEnabled() ) {
			return true;
		}
		if ( Redis::isEnabled() ) {
			return true;
		}
		return false;
	}

	/**
	 * Purge plugin cache including server cache.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function purge() : bool
	{
		if ( !self::isActive() ) {
			return false;
		}

		/**
		 * Purge Opcache.
		 */
		if ( Opcache::isEnabled() ) {
			Opcache::purge();
		}

		/**
		 * Purge APCu.
		 */
		if ( Apcu::isEnabled() ) {
			Apcu::purge();
		}
 
		/**
		 * Purge Redis.
		 */
		if ( Redis::isEnabled() ) {
			Redis::purge();
		}

		/**
		 * Purge WP Rocket.
		 */
		if ( WpRocket::isEnabled() ) {
			WpRocket::purge();
		}
			    
		/**
		 * Purge LiteSpeed.
		 */
		if ( LiteSpeed::isEnabled() ) {
			LiteSpeed::purge();
		}

		/**
		 * Purge W3 Total Cache.
		 */
		if ( W3TotalCache::isEnabled() ) {
			W3TotalCache::purge();
		}

		/**
		 * Purge WP-Optimize.
		 */
		if ( WpOptimize::isEnabled() ) {
			WpOptimize::purge();
		}

		/**
		 * Purge WP Fastest Cache.
		 */
		if ( WpFastestCache::isEnabled() ) {
			WpFastestCache::purge();
		}

		/**
		 * Purge WP Super Cache.
		 */
		if ( WpSuperCache::isEnabled() ) {
			WpSuperCache::purge();
		}

		/**
		 * Purge Cache Enabler.
		 */
		if ( CacheEnabler::isEnabled() ) {
			CacheEnabler::purge();
		}

		/**
		 * Purge Kinsta.
		 */
		if ( Kinsta::isEnabled() ) {
			Kinsta::purge();
		}

		return true;
	}

	/**
	 * Load geotargeting cookie before third-party cache.
	 * [Action: init].
	 * [Action: load].
	 *
	 * @access public
	 * @param string $name, Cookie name
	 * @return bool
	 */
	public static function loadGeotargeting(string $name) : bool
	{
		if ( self::isActive() ) {

			/**
			 * WP Rocket Geotargeting.
			 */
			if ( WpRocket::isEnabled() ) {
				WpRocket::loadGeotargeting($name);
				return true;
			}

		}
		return false;
	}

	/**
	 * Enable geotargeting through third-party cache.
	 * [Action: {plugin}-activate].
	 *
	 * @access public
	 * @param string $name, Cookie name
	 * @return bool
	 */
	public static function enableGeotargeting(string $name) : bool
	{
		if ( self::isActive() ) {

			/**
			 * WP Rocket Geotargeting.
			 */
			if ( WpRocket::isEnabled() ) {
				WpRocket::enableGeotargeting($name);
				return true;
			}

		}
		return false;
	}

	/**
	 * Disable third-party cache geotargeting.
	 * [Action: {plugin}-deactivate].
	 *
	 * @access public
	 * @param string $name, Cookie name
	 * @return bool
	 */
	public static function disableGeotargeting(string $name) : bool
	{
		if ( self::isActive() ) {

			/**
			 * WP Rocket Geotargeting.
			 */
			if ( WpRocket::isEnabled() ) {
				WpRocket::disableGeotargeting($name);
				return true;
			}

		}
		return false;
	}
}
