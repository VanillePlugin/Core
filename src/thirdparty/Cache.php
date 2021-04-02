<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.1
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\thirdparty;

final class Cache
{
	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isActive()
	{
		// Check WordPress Cache
		if ( !defined('WP_CACHE') || WP_CACHE == false ) {
			return false;
		}
		return true;
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function purge()
	{
		if ( !self::isActive() ) {
			return;
		}

		/**
		 * Clear Opcode cache
		 * @see https://www.php.net/manual/fr/book.opcache.php
		 */
		if ( function_exists('opcache_reset') ) {
			opcache_reset();
		}
 
		/**
		 * Clear APCu cache
		 * @see https://www.php.net/manual/fr/function.apcu-clear-cache.php
		 */
		if ( function_exists('apcu_clear_cache') ) {
			apcu_clear_cache();
		}

		/**
		 * Purge WP Rocket
		 * @see https://github.com/wp-media/wp-rocket
		 */
		if ( function_exists('rocket_clean_domain') ) {
			rocket_clean_domain();
		}

		/**
		 * Purge W3 Total Cache
		 * @see https://github.com/W3EDGE/w3-total-cache
		 */
	    if ( function_exists('w3tc_pgcache_flush') ) { 
	        w3tc_pgcache_flush(); 
	    }

		/**
		 * Purge LiteSpeed
		 * @see https://github.com/litespeedtech/lscache_wp
		 */
		if ( class_exists('\LiteSpeed\Purge') ) {
			\LiteSpeed\Purge::purge_all();
		}

		/**
		 * Purge WP-Optimize
		 * @see https://github.com/DavidAnderson684/WP-Optimize
		 */
		if ( class_exists('WP_Optimize_Cache_Commands') ) {
            $wpoptimize = new \WP_Optimize_Cache_Commands();
            $wpoptimize->purge_page_cache();
		}
 
		/**
		 * Purge Kinsta Cache
		 * @see https://kinsta.com/knowledgebase/kinsta-mu-plugin/
		 */
		if ( class_exists('\Kinsta\Cache') ) {
			global $kinsta_cache;
            if ( !empty($kinsta_cache) ) {
            	$kinsta_cache->kinsta_cache_purge->purge_complete_caches();
            }
		}

		/**
		 * Purge WP Fastest Cache
		 * @see https://github.com/emrevona/wp-fastest-cache
		 */
		if ( method_exists('WpFastestCache','deleteCache') ) {
			global $wp_fastest_cache;
            if ( !empty($wp_fastest_cache) ) {
            	$wp_fastest_cache->deleteCache();
            }
		}

		/**
		 * Purge WP Super Cache
		 * @see https://github.com/Automattic/wp-super-cache
		 */
		if ( function_exists('wp_cache_clean_cache') ) {
		    global $file_prefix, $supercachedir;
		    if ( function_exists('get_supercache_dir') && empty($supercachedir) ) {
		        $supercachedir = get_supercache_dir();
		    }
		    wp_cache_clean_cache($file_prefix);
		}

		/**
		 * Purge Cache Enabler
		 * @see https://github.com/keycdn/cache-enabler
		 */
		if ( class_exists('Cache_Enabler') ) {
			\Cache_Enabler::clear_complete_cache();
		}
	}
}
