<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.2
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\thirdparty\inc\plugin;

/**
 * Cache Enabler plugin helper class.
 * 
 * @see https://github.com/keycdn/cache-enabler
 */
final class CacheEnabler
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
		return defined('CACHE_ENABLER_VERSION');
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
		if ( class_exists('\Cache_Enabler') ) {
			\Cache_Enabler::clear_complete_cache();
			return true;
		}
		return false;
	}
}
