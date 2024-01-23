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
 * WPML plugin helper class.
 * 
 * @see https://github.com/wp-premium/wpml-media
 */
final class Wpml
{
	/**
	 * Check whether plugin is enabled.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return defined('ICL_SITEPRESS_VERSION');
	}

	/**
	 * Check whether plugin is active (functional).
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isActive() : bool
	{
		global $sitepress;
		if ( !empty($sitepress) && is_object($sitepress) ) {
			if ( method_exists($sitepress,'get_active_languages') ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get active languages.
	 * 
	 * @access public
	 * @return mixed
	 */
	public static function getActiveLanguages()
	{
		return array_keys(
			$GLOBALS['sitepress']->get_active_languages()
		);
	}

	/**
	 * Get current locale.
	 * 
	 * @access public
	 * @return mixed
	 */
	public static function getLocale()
	{
		return apply_filters('wpml_current_language', null);
	}
}
