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

namespace VanillePlugin\tr;

use VanillePlugin\inc\{
	GlobalConst, Plugin, Theme
};
use VanillePlugin\lib\Orm;

trait TraitConfigurable
{
	/**
	 * Register settings.
	 *
	 * @access protected
	 * @param string $group
	 * @param string $key
	 * @param array $args
	 * @return void
	 */
	protected function registerOption(string $group, string $key, array $args = [])
	{
		register_setting($group, $key, $args);
	}

	/**
	 * Add option.
	 *
	 * @access protected
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	protected function addOption(string $key, $value) : bool
	{
		return add_option($key, $value);
	}

	/**
	 * Get option.
	 *
	 * @access protected
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getOption(string $key, $default = false)
	{
		return get_option($key, $default);
	}

	/**
	 * Update option.
	 *
	 * @access protected
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	protected function updateOption(string $key, $value) : bool
	{
		return update_option($key, $value);
	}

	/**
	 * Remove option.
	 *
	 * @access protected
	 * @param string $key
	 * @return bool
	 */
	protected function removeOption(string $key) : bool
	{
		return delete_option($key);
	}

	/**
	 * Remove all namespace options.
	 *
	 * @access protected
	 * @param string $namespace
	 * @return bool
	 */
	protected function removeOptions(string $namespace) : bool
	{
		if ( !$namespace ) {
			return false;
		}
		$db = new Orm();
		$sql = "DELETE FROM {$db->prefix}options WHERE `option_name` LIKE '%{$namespace}_%'";
		return (bool)$db->execute($sql);
	}

	/**
	 * Get site version.
	 * 
	 * @access protected
	 * @return string
	 */
	protected function getVersion() : string
	{
		return GlobalConst::version();
	}

	/**
	 * Get site debug status.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isDebug() : bool
	{
		return GlobalConst::debug();
	}
	
	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function isMultisite() : bool
	{
		return GlobalConst::multisite();
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function isMobile() : bool
	{
		return GlobalConst::mobile();
	}

	/**
	 * Get admin url.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getAdminUrl(string $url = null, string $scheme = 'admin') : string
	{
		return GlobalConst::ajaxUrl($url, $scheme);
	}

	/**
	 * Get ajax url.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getAjaxUrl(string $scheme = 'admin') : string
	{
		return GlobalConst::ajaxUrl($scheme);
	}

	/**
	 * Get front url.
	 * 
	 * @access protected
	 * @param string $path
	 * @param string $scheme
	 * @return string
	 */
	protected function getFrontUrl(?string $path = null, ?string $scheme = null) : string
	{
		return GlobalConst::url($path, $scheme);
	}

	/**
	 * Get site base url.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function geSiteUrl(?string $path = null, string $scheme = 'relative') : string
	{
		return GlobalConst::siteUrl($path, $scheme);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getPluginUrl(string $path) : string
	{
		return Plugin::getUrl($path);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getPluginDir(string $path) : string
	{
		return Plugin::getDir($path);
	}

	/**
	 * Get plugin header using file.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getPluginHeader(string $file) : array
	{
		return Plugin::getHeader($file);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getThemeUrl(?string $path = null) : string
	{
		return Theme::getUrl($path);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getThemeDir(?string $path = null) : string
	{
		return Theme::getDir($path);
	}
}
