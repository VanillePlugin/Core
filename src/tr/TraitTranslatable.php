<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\{
	Globals, Stringify
};
use VanilleThird\Translator;

trait TraitTranslatable
{
	/**
	 * Get site locale.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getLocale($user = null) : string
	{
		return Globals::locale($user);
	}

	/**
	 * Get translator locale.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getTranslatorLocale() : string
	{
		return (string)Translator::getLocale();
	}
	
	/**
	 * Check whether translator is active.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasTranslator() : bool
	{
		return (bool)Translator::isActive();
	}

	/**
	 * Normalize locale.
	 *
	 * @access public
	 * @param string $locale
	 * @return string
	 */
	public static function normalizeLocale(string $locale) : string
	{
		$locale = Stringify::slugify($locale);
		$locale = Stringify::replace('_', '-', $locale);
		if ( !Stringify::contains($locale, '-') ) {
			$locale = "{$locale}-{$locale}";
		}
		return $locale;
	}

	/**
	 * Parse lang.
	 *
	 * @access public
	 * @param string $locale
	 * @return string
	 */
	public static function parseLang(string $locale) : string
	{
		if ( Stringify::contains($locale, '-') ) {
			if ( ($locale = explode('-', $locale)) ) {
				$locale = $locale[0] ?? '';
			}
		}
		return $locale;
	}
}
