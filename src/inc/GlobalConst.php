<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.5
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class GlobalConst
{
	/**
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function pluginDir($path = null)
	{
		if ( $path ) {
			return Stringify::formatPath(WP_PLUGIN_DIR .'/'. $path);
		}
		return WP_PLUGIN_DIR;
	}

	/**
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function pluginMuDir($path = null)
	{
		if ( $path ) {
			return Stringify::formatPath(WPMU_PLUGIN_DIR .'/'. $path);
		}
		return WPMU_PLUGIN_DIR;
	}

	/**
	 * @access public
	 * @param string $url
	 * @return string
	 */
	public static function pluginUrl($url = null)
	{
		if ( $url ) {
			return Stringify::formatUrl(WP_PLUGIN_URL .'/'. $url);
		}
		return WP_PLUGIN_URL;
	}
	
	/**
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function contentDir($path = null)
	{
		if ( $path ) {
			return Stringify::formatPath(WP_CONTENT_DIR .'/'. $path);
		}
		return WP_CONTENT_DIR;
	}

	/**
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function rootDir($path = null)
	{
		if ( $path ) {
			return Stringify::formatPath(ABSPATH .'/'. $path);
		}
		return ABSPATH;
	}

	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function debug()
	{
		return WP_DEBUG;
	}
}
