<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

final class Localization
{
	/**
	 * Get site locale.
	 *
	 * @access public
	 * @param mixed $user
	 * @return string
	 */
	public static function getLocale($user = null): string
	{
		if ($user) {
			return get_user_locale($user);
		}
		return get_locale();
	}

	/**
	 * Load translation.
	 *
	 * @access public
	 * @param string $domain
	 * @param string $mo
	 * @return bool
	 */
	public static function load(string $domain, string $mo): bool
	{
		return load_textdomain($domain, $mo);
	}

	/**
	 * Load plugin translation.
	 *
	 * @access public
	 * @param string $domain
	 * @param string $path
	 * @return bool
	 */
	public static function loadPlugin(string $domain, ?string $path = null): bool
	{
		return load_plugin_textdomain($domain, false, (string)$path);
	}

	/**
	 * Load theme translation.
	 *
	 * @access public
	 * @param string $domain
	 * @param string $path
	 * @return bool
	 */
	public static function loadTheme(string $domain, ?string $path = null): bool
	{
		return load_theme_textdomain($domain, (string)$path);
	}

	/**
	 * Load chile translation.
	 *
	 * @access public
	 * @param string $domain
	 * @param string $path
	 * @return bool
	 */
	public static function loadChild(string $domain, ?string $path = null): bool
	{
		return load_child_theme_textdomain($domain, (string)$path);
	}

	/**
	 * Parse translation file.
	 *
	 * @access public
	 * @param string $domain
	 * @param string $locale
	 * @return string
	 */
	public static function parse(string $domain, string $locale): string
	{
		return sprintf('/%1$s-%2$s.mo', $domain, $locale);
	}

	/**
	 * Parse lang from locale.
	 *
	 * @access public
	 * @param string $locale
	 * @return string
	 */
	public static function parseLang(string $locale): string
	{
		$locale = self::normalizeLocale($locale);
		if (Stringify::contains($locale, '-')) {
			if (($locale = explode('-', $locale))) {
				$locale = $locale[0] ?? '';
			}
		}
		return $locale;
	}

	/**
	 * Parse region from locale.
	 *
	 * @access public
	 * @param string $locale
	 * @return string
	 */
	public static function parseRegion(string $locale): string
	{
		$locale = self::normalizeLocale($locale);
		if (Stringify::contains($locale, '-')) {
			if (($locale = explode('-', $locale))) {
				$locale = $locale[1] ?? '';
			}
		}
		return $locale;
	}

	/**
	 * Normalize locale.
	 *
	 * @access public
	 * @param string $locale
	 * @return string
	 */
	public static function normalizeLocale(string $locale): string
	{
		$locale = Stringify::slugify($locale);
		return Stringify::replace('_', '-', $locale);
	}
}
