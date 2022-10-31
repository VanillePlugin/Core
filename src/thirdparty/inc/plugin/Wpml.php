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

namespace VanillePlugin\thirdparty\inc\plugin;

/**
 * WPML Helper Class.
 * 
 * @see https://github.com/wp-premium/wpml-media
 */
final class Wpml
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
		return defined('ICL_SITEPRESS_VERSION');
	}

	/**
	 * Check whether plugin is active (functional).
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isActive()
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
	 * @param void
	 * @return mixed
	 */
	public static function getActiveLanguages()
	{
		return array_keys(
			$GLOBALS['sitepress']->get_active_languages()
		);
	}

	/**
	 * Get current language.
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function getCurrentLanguage()
	{
		return apply_filters('wpml_current_language',null);
	}
}
