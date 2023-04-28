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

namespace VanillePlugin\thirdparty\inc\plugin;

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
	 * @param void
	 * @return bool
	 */
	public static function isEnabled()
	{
		return defined('WPCACHECONFIGPATH');
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
