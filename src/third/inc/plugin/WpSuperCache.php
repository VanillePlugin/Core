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

namespace VanillePlugin\third\inc\plugin;

/**
 * WP Super Cache plugin helper class.
 * 
 * @see https://github.com/Automattic/wp-super-cache
 */
final class WpSuperCache
{
	/**
	 * Check whether plugin is enabled.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return defined('WPCACHECONFIGPATH');
	}

	/**
	 * Purge cache.
	 * 
	 * @access public
	 * @return bool
	 * @internal
	 */
	public static function purge() : bool
	{
		if ( function_exists('wp_cache_clean_cache') ) {
		    global $file_prefix, $supercachedir;
		    if ( function_exists('get_supercache_dir') && empty($supercachedir) ) {
		        $supercachedir = get_supercache_dir();
		    }
		    wp_cache_clean_cache($file_prefix);
		    return true;
		}
		return false;
	}
}
