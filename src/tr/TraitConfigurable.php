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

namespace VanillePlugin\tr;

use VanillePlugin\inc\{
	Globals,
	Page,
	Plugin,
	Theme
};
use VanillePlugin\lib\Orm;

/**
 * Define base configuration.
 */
trait TraitConfigurable
{
	/**
	 * Get option.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getOption(string $key, $default = false)
	{
		return get_option($key, $default);
	}

	/**
	 * Check option exists.
	 *
	 * @access public
	 * @param string $key
	 * @return bool
	 */
	public function isOption(string $key): bool
	{
		$value = $this->getOption($key, 'missing-option');
		return ($value !== 'missing-option');
	}

	/**
	 * Check whether page is admin.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isAdmin(): bool
	{
		return Page::isAdmin();
	}

	/**
	 * Check whether page is login.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isLogin(): bool
	{
		return Page::isLogin();
	}

	/**
	 * Get site debug status.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isDebug(): bool
	{
		return Globals::debug();
	}

	/**
	 * Get site installing status.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isInstalling(): bool
	{
		return Globals::installing();
	}

	/**
	 * Check whether multisite is enabled.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isMultisite(): bool
	{
		return Globals::multisite();
	}

	/**
	 * Check mobile device.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isMobile(): bool
	{
		return Globals::mobile();
	}

	/**
	 * Check ajax request.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isAjax(): bool
	{
		return Globals::ajax();
	}

	/**
	 * Check REST API endpoint.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isApi(): bool
	{
		return Globals::api();
	}

	/**
	 * Get site version.
	 *
	 * @access public
	 * @return string
	 */
	public function getSiteVersion(): string
	{
		return Globals::version();
	}

	/**
	 * Get site roles.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getSiteRoles(): array
	{
		return Globals::roles();
	}

	/**
	 * Get admin URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getAdminUrl(?string $path = null, string $scheme = 'admin'): string
	{
		return Globals::adminUrl($path, $scheme);
	}

	/**
	 * Get REST URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getRestUrl(?string $path = null): string
	{
		return Globals::restUrl($path);
	}

	/**
	 * Get Ajax URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getAjaxUrl(string $scheme = 'admin'): string
	{
		return Globals::ajaxUrl($scheme);
	}

	/**
	 * Get front URL.
	 *
	 * @access public
	 * @param string $path
	 * @param string $scheme
	 * @return string
	 */
	public function getFrontUrl(?string $path = null, ?string $scheme = null): string
	{
		return Globals::url($path, $scheme);
	}

	/**
	 * Get site base URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function geSiteUrl(?string $path = null, string $scheme = 'relative'): string
	{
		return Globals::siteUrl($path, $scheme);
	}

	/**
	 * Get site domain name.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function geSiteDomain(): string
	{
		return Globals::siteDomain();
	}

	/**
	 * Get plugin URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getPluginUrl(string $path): string
	{
		return Plugin::getUrl($path);
	}

	/**
	 * Get plugin directory.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getPluginDir(string $path): string
	{
		return Plugin::getDir($path);
	}

	/**
	 * Get MU plugin directory.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getPluginMuDir(string $path): string
	{
		return Plugin::getMuDir($path);
	}

	/**
	 * Get plugin header using file.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getPluginHeader(string $file): array
	{
		return Plugin::getHeader($file);
	}

	/**
	 * Get active theme URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getThemeUrl(?string $path = null): string
	{
		return Theme::getUrl($path);
	}

	/**
	 * Get active theme URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getThemeDir(?string $path = null): string
	{
		return Theme::getDir($path);
	}

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
	protected function addOption(string $key, $value): bool
	{
		return add_option($key, $value);
	}

	/**
	 * Update option.
	 *
	 * @access protected
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	protected function updateOption(string $key, $value): bool
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
	protected function removeOption(string $key): bool
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
	protected function removeOptions(string $namespace): bool
	{
		if (!$namespace) {
			return false;
		}
		$db  = new Orm();
		$sql = "DELETE FROM {$db->prefix}options WHERE `option_name` LIKE '%{$namespace}_%'";
		return (bool)$db->execute($sql);
	}
}
