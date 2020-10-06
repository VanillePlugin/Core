<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.4
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
	 * @return void
	 */
	static public function purge()
	{
		// Check WordPress Cache
		if ( !defined('WP_CACHE') || WP_CACHE == false ) return;
		
		// Purge WP-Rocket
		if ( function_exists('rocket_clean_domain') ) {
			rocket_clean_domain();
		}

		// Purge W3 Total Cache
	    if ( function_exists('w3tc_pgcache_flush') ) { 
	        w3tc_pgcache_flush(); 
	    }

		// Purge LiteSpeed
		if ( class_exists('LiteSpeed_Cache') ) {
			\LiteSpeed_Cache_API::purge_all();
		}

		// Purge WP-Optimize
		if ( class_exists('WP_Optimize_Cache_Commands') ) {
            $wpoptimize = new \WP_Optimize_Cache_Commands();
            $wpoptimize->purge_page_cache();
		}

		// Purge Kinsta Cache
		if ( class_exists('\Kinsta\Cache') ) {
			global $kinsta_cache;
            if ( !empty($kinsta_cache) ) {
            	$kinsta_cache->kinsta_cache_purge->purge_complete_caches();
            }
		}

		// Purge WP Fastest Cache
		if ( method_exists('WpFastestCache','deleteCache') ) {
			global $wp_fastest_cache;
            if ( !empty($wp_fastest_cache) ) {
            	$wp_fastest_cache->deleteCache();
            }
		}

		// Purge WP Super Cache
		if ( function_exists('wp_cache_clean_cache') ) {
		    global $file_prefix, $supercachedir;
		    if ( function_exists('get_supercache_dir') && empty($supercachedir) ) {
		        $supercachedir = get_supercache_dir();
		    }
		    wp_cache_clean_cache($file_prefix);
		}
	}
}
