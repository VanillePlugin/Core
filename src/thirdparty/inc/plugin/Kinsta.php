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
 * Kinsta (MU) Helper Class.
 * 
 * @see https://github.com/retlehs/kinsta-mu-plugins
 */
final class Kinsta
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
		return defined('KINSTAMU_VERSION');
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
		if ( class_exists('\Kinsta\Cache') ) {
			global $kinsta_cache;
            if ( is_object($kinsta_cache) ) {
            	$kinsta_cache->kinsta_cache_purge->purge_complete_caches();
            }
            return true;
		}
		return false;
	}
}
