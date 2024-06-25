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

use VanillePlugin\inc\Localisation;
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
		return Localisation::getLocale($user);
	}

	/**
	 * Load translation.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function loadTranslation(string $domain, string $mo) : bool
	{
		return Localisation::load($domain, $mo);
	}

	/**
	 * Load plugin translation.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function loadPluginTranslation(string $domain, ?string $path = null) : bool
	{
		return Localisation::loadPlugin($domain, $path);
	}

	/**
	 * Parse translation file.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function parseTranslationFile(string $domain) : string
	{
		return Localisation::parse($domain, $this->getLocale());
	}

	/**
	 * Load theme translation.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function loadThemeTranslation(string $domain, ?string $path = null) : bool
	{
		return Localisation::loadTheme($domain, $path);
	}

	/**
	 * Load child translation.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function loadChildTranslation(string $domain, ?string $path = null) : bool
	{
		return Localisation::loadChild($domain, $path);
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
	 * @access protected
	 * @param string $locale
	 * @return string
	 */
	protected function normalizeLocale(string $locale) : string
	{
		$lang   = Localisation::parseLang($locale);
		$region = Localisation::parseRegion($locale);

		if ( $lang === $region ) {
			return $lang;
		}
		
		return Localisation::normalizeLocale($locale);
	}
}
