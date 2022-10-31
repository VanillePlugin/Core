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

namespace VanillePlugin\thirdparty;

use VanillePlugin\thirdparty\inc\plugin\Wpml;
use VanillePlugin\thirdparty\inc\plugin\Polylang;
use VanillePlugin\thirdparty\inc\plugin\Qtranslate;

/**
 * Third-Party Translator Helper Class.
 */
final class Translator
{
	/**
	 * Check whether translator is active (functional).
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function isActive()
	{
		/**
		 * Check WPML.
		 */
		if ( Wpml::isEnabled() && Wpml::isActive() ) {
			return 'wpml';
		}

		/**
		 * Check Polylang.
		 */
		if ( Polylang::isEnabled() && Polylang::isActive() ) {
			return 'polylang';
		}

		/**
		 * Check qTranslate.
		 */
		if ( Qtranslate::isEnabled() && Qtranslate::isActive() ) {
			return 'qtranslate';
		}

		return false;
	}

	/**
	 * Get translator active languages.
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function getActiveLanguages()
	{
		if ( ($plugin = self::isActive()) ) {
			if ( $plugin == 'wpml' ) {
				return Wpml::getActiveLanguages();
			}
			if ( $plugin == 'polylang' ) {
				return Polylang::getActiveLanguages();
			}
			if ( $plugin == 'qtranslate' ) {
				return Qtranslate::getActiveLanguages();
			}
		}
		return false;
	}

	/**
	 * Get translator current language.
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function getCurrentLanguage()
	{
		if ( ($plugin = self::isActive()) ) {
			if ( $plugin == 'wpml' ) {
				return Wpml::getCurrentLanguage();
			}
			if ( $plugin == 'polylang' ) {
				return Polylang::getCurrentLanguage();
			}
		}
		return false;
	}
}
