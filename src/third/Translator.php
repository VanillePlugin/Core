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

namespace VanillePlugin\third;

use VanillePlugin\inc\{
	Stringify, TypeCheck
};
use VanillePlugin\third\inc\plugin\{
	Wpml, Polylang, Qtranslate
};

/**
 * Third-Party translator helper class.
 */
final class Translator
{
	/**
	 * Check whether translator is active (functional).
	 * 
	 * @access public
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
	 * Get translator locale.
	 * 
	 * @access public
	 * @return mixed
	 */
	public static function getLocale()
	{
		if ( ($plugin = self::isActive()) ) {
			if ( $plugin == 'wpml' ) {
				return Wpml::getLocale();
			}
			if ( $plugin == 'polylang' ) {
				return Polylang::getLocale();
			}
		}
		return false;
	}

	/**
	 * Sanitize translator locale.
	 * 
	 * @access public
	 * @param string $locale
	 * @return string
	 */
	public static function sanitizeLocale(string $locale) : string
	{
		$locale = Stringify::lowercase($locale);
		$locale = Stringify::replace('-', '_', $locale);
		return $locale;
	}

	/**
	 * Get normalized country code.
	 * 
	 * @access public
	 * @param string $locale
	 * @return string
	 */
	public static function getCountry(string $locale) : string
	{
		$locale = self::sanitizeLocale($locale);
		if ( strpos($locale, '_') !== false ) {
			$locale = explode('_', $locale);
		}

		if ( TypeCheck::isArray($locale) ) {
			if ( count($locale) == 2 ) {
				$country = $locale[1];

			} else {
				$country = $locale[0];
			}

		} else {
			$country = $locale;
		}

		switch ($country) {
			case 'gb':
			case 'en':
			case 'en-gb':
				$country = 'uk';
				break;
			case 'en-us':
				$country = 'us';
				break;
		}

		return $country;
	}

	/**
	 * Get normalized language code.
	 * 
	 * @access public
	 * @param string $locale
	 * @return string
	 */
	public static function getLanguage(string $locale) : string
	{
		$locale = self::sanitizeLocale($locale);
		if ( strpos($locale, '_') !== false ) {
			$locale = explode('_', $locale);
		}

		if ( TypeCheck::isArray($locale) ) {
			$lang = $locale[0];

		} else {
			$lang = $locale;
		}

		return $lang;
	}
}
