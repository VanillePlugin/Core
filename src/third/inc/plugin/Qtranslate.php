<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\third\inc\plugin;

/**
 * QTranslate plugin helper class.
 * 
 * @see https://github.com/qtranslate/qtranslate-xt
 */
final class Qtranslate
{
	/**
	 * Check whether plugin is enabled.
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return defined('QTX_VERSION');
	}

	/**
	 * Check whether plugin is active (functional).
	 * 
	 * @access public
	 * @return bool
	 */
	public static function isActive() : bool
	{
		global $q_config;
		if ( !empty($q_config) && is_array($q_config) ) {
			if ( function_exists('qtranxf_convertURL') ) {
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
		return $GLOBALS['q_config']['enabled_languages'] ?? false;
	}

	/**
	 * Get current locale.
	 * 
	 * @access public
	 * @return mixed
	 */
	public static function getLocale()
	{
		return false;
	}
}
