<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\thirdparty;

use VanillePlugin\thirdparty\inc\module\Opcache;
use VanillePlugin\thirdparty\inc\module\APCu;
use VanillePlugin\thirdparty\inc\plugin\WpRocket;
use VanillePlugin\thirdparty\inc\plugin\LiteSpeed;
use VanillePlugin\thirdparty\inc\plugin\WpOptimize;
use VanillePlugin\thirdparty\inc\plugin\W3TotalCache;
use VanillePlugin\thirdparty\inc\plugin\Kinsta;
use VanillePlugin\thirdparty\inc\plugin\WpFastestCache;
use VanillePlugin\thirdparty\inc\plugin\WpSuperCache;
use VanillePlugin\thirdparty\inc\plugin\CacheEnabler;

/**
 * Third-Party Cache Helper Class.
 */
final class Cache
{
	/**
	 * Check whether cache is active (functional),
	 * Using WordPress cache constant.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isActive()
	{
		if ( !defined('WP_CACHE') || WP_CACHE == false ) {
			return false;
		}
		return true;
	}

	/**
	 * Purge plugin cache including server cache,
	 * Purge: Can either be flush or clear or both.
	 * 
	 * @access public
	 * @param void
	 * @return bool (internal)
	 */
	public static function purge()
	{
		if ( !self::isActive() ) {
			return false;
		}

		/**
		 * Purge Opcode.
		 */
		Opcache::purge();
 
		/**
		 * Purge APCu.
		 */
		APCu::purge();

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
		 * Purge Kinsta.
		 */
		if ( Kinsta::isEnabled() ) {
			Kinsta::purge();
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

		return false;
	}

	/**
	 * Fix geolocation under cache.
	 * 
	 * @access public
	 * @param void
	 * @return bool (internal)
	 */
	public static function geolocate()
	{
		if ( !self::isActive() ) {
			return false;
		}

		return false;
	}
}
