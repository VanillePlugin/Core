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
 * Polylang Helper Class.
 * 
 * @see https://github.com/polylang/polylang
 */
final class Polylang
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
		return defined('POLYLANG_VERSION');
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
		global $polylang;
		if ( !empty($polylang) && function_exists('pll_languages_list') ) {
			if ( !empty(pll_languages_list()) ) {
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
		return pll_languages_list();
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
		return pll_current_language();
	}
}
