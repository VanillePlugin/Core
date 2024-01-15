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

namespace VanillePlugin\tr;

use VanillePlugin\inc\GlobalConst;
use VanillePlugin\third\Translator;

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
		return GlobalConst::locale($user);
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
	 * Get normalized country code.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function getTranslatorCountry(string $locale) : string
	{
		return Translator::getCountry($locale);
	}
	
	/**
	 * Get normalized language code.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getLanguage(string $locale) : string
	{
		return Translator::getLanguage($locale);
	}
	
	/**
	 * Check whether translator is active (functional).
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasTranslator() : bool
	{
		return (bool)Translator::isActive();
	}
}
