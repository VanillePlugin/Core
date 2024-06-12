<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

final class Theme
{
	/**
	 * Get active theme URL without trailing slash.
	 * 
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function getUrl(?string $path = null) : string
	{
		$baseUrl = get_stylesheet_directory_uri();
		if ( $path ) {
			return Stringify::formatPath("{$baseUrl}/{$path}", true);
		}
		return $baseUrl;
	}

	/**
	 * Get active theme directory without trailing slash.
	 * 
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function getDir(?string $path = null) : string
	{
		$baseDir = get_stylesheet_directory();
		if ( $path ) {
			return Stringify::formatPath("{$baseDir}/{$path}", true);
		}
		return $baseDir;
	}
}
