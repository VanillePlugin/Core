<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.4.4
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\thirdparty;

use VanillePlugin\inc\TypeCheck;

final class Translator
{
	/**
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function isActive()
	{
		// Check WordPress Translator
		global $sitepress, $q_config, $polylang;

		/**
		 * Check WPML
		 * @see https://github.com/wp-premium/wpml-media
		 */
		if ( !empty($sitepress) && TypeCheck::isObject($sitepress) ) {
			if ( method_exists($sitepress,'get_active_languages') ) {
				return 'wpml';
			}
		}

		/**
		 * Check Polylang
		 * @see https://github.com/polylang/polylang
		 */
		if ( !empty($polylang) && function_exists('pll_languages_list') ) {
			$languages = pll_languages_list();
			if ( empty($languages) ) {
				return false;
			}
			return 'polylang';
		}

		/**
		 * Check qTranslate
		 * @see https://github.com/qtranslate/qtranslate-xt
		 */
		if ( !empty($q_config) && TypeCheck::isArray($q_config) ) {
			if ( function_exists('qtranxf_convertURL') ) {
				return 'qtranslate-x';
			}
			if ( function_exists('qtrans_convertURL') ) {
				return 'qtranslate';
			}
		}

		return false;
	}

	/**
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function getActiveLanguages()
	{
		if ( ($code = self::isActive()) ) {
			if ( $code == 'wpml' ) {
				return array_keys($GLOBALS['sitepress']->get_active_languages());
			}
			if ( $code == 'polylang' ) {
				return pll_languages_list();
			}
			if ( $code == 'qtranslate' || $code == 'qtranslate-x' ) {
				return !empty($GLOBALS['q_config']['enabled_languages']) 
				? $GLOBALS['q_config']['enabled_languages'] : [];
			}
		}
		return false;
	}

	/**
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function getCurrentLanguage()
	{
		if ( ($code = self::isActive()) ) {
			if ( $code == 'wpml' ) {
				return apply_filters('wpml_current_language',null);
			}
			if ( $code == 'polylang' ) {
				if ( function_exists('pll_current_language') ) {
					return pll_current_language();
				}
			}
		}
		return false;
	}
}
