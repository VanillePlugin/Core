<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

final class Plugin
{
	/**
	 * Deactivate plugin(s).
	 *
	 * @access public
	 * @param array $plugins
	 * @param bool $silent
	 * @return void
	 */
	public static function deactivate(array $plugins = [], bool $silent = true)
	{
		deactivate_plugins($plugins, $silent);
	}

	/**
	 * Get plugin URL without trailing slash,
	 * Path must include plugin name.
	 * 
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function getUrl(string $path) : string
	{
		$baseUrl = Globals::pluginUrl();
		return Stringify::formatPath("{$baseUrl}/{$path}", true);
	}

	/**
	 * Get plugin directory without trailing slash,
	 * Path must include plugin name.
	 * 
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function getDir(string $path) : string
	{
		$baseDir = Globals::pluginDir();
		return Stringify::formatPath("{$baseDir}/{$path}", true);
	}

	/**
	 * Get MU plugin directory without trailing slash,
	 * Path must include plugin file name.
	 * 
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function getMuDir(string $path) : string
	{
		$baseDir = Globals::pluginMuDir();
		return Stringify::formatPath("{$baseDir}/{$path}", true);
	}

	/**
	 * Get plugin header using file,
	 * [{pluginDir}/{pluginMain}.php].
	 *
	 * @access public
	 * @param string $file
	 * @param bool $markup
	 * @param bool $translate
	 * @return array
	 */
	public static function getHeader(string $file, bool $markup = true, bool $translate = true) : array
	{
	    if ( !TypeCheck::isFunction('get_plugin_data') ) {
	        require_once Globals::rootDir('wp-admin/includes/plugin.php');
	    }
	    $file = self::getDir($file);
		return get_plugin_data($file, $markup, $translate);
	}

	/**
	 * Get plugin data using file,
	 * [{pluginDir}/{pluginMain}.php].
	 *
	 * @access public
	 * @param string $file
	 * @param array $header
	 * @param bool $context
	 * @return array
	 */
	public static function getData(string $file, array $header = [], bool $context = false) : array
	{
		if ( empty($header) ) {
			$header['version'] = 'Version';
		}
		$file = self::getDir($file);
		return get_file_data($file, $header, $context);
	}
}
