<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.2
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\VanilleConfig;
use VanillePlugin\lib\WordPress;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\Post;
use VanillePlugin\inc\Get;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Server;

class PluginOptions extends WordPress
{
	use VanilleConfig;

	/**
	 * Register a settings and its data
	 *
	 * @access protected
	 * @param {inherit}
	 * @return {inherit}
	 */
	protected function doPluginAction($action, $args = null)
	{
		return $this->doAction("{$this->getNameSpace()}-{$action}", $args);
	}

	/**
	 * Register Plugin Settings
	 *
	 * @access protected
	 * @param inherit
	 * @return inherit
	 */
	protected function registerPluginOption($group, $option, $args = null)
	{
		$this->registerOption("{$this->getPrefix()}{$group}","{$this->getPrefix()}{$option}",$args);
	}

	/**
	 * Addd Plugin Option
	 *
	 * @access protected
	 * @param inherit
	 * @return inherit
	 */
	protected function addPluginOption($option, $value)
	{
		return $this->addOption("{$this->getPrefix()}{$option}",$value);
	}

	/**
	 * Retrieves an option value based on an option name
	 *
	 * @access protected
	 * @param string $option
	 * @param string $type
	 * @return mixed
	 */
	protected function getPluginOption($option, $type = 'array')
	{
		$value = $this->getOption("{$this->getPrefix()}{$option}", false);
		switch ($type) {
			case 'integer':
				return intval($value);
				break;

			case 'float':
				return floatval($value);
				break;

			case 'boolean':
				return boolval($value);
				break;
		}
		return $value;
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @access protected
	 * @param string $option
	 * @param mixed $value
	 * @return {inherit}
	 */
	protected function updatePluginOption($option, $value)
	{
		return $this->updateOption("{$this->getPrefix()}{$option}",$value);
	}

	/**
	 * Remove Plugin Option
	 *
	 * @access protected
	 * @param string $option
	 * @return {inherit}
	 */
	protected function removePluginOption($option)
	{
		return $this->removeOption("{$this->getPrefix()}{$option}");
	}

	/**
	 * Retrieves an option value based on an option name as object
	 *
	 * @access protected
	 * @param string $option
	 * @return {inherit}
	 */
	protected function getPluginObject($option)
	{
		return Stringify::toObject( $this->getPluginOption($option) );
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @access protected
	 * @param string $icon
	 * @return {inherit}
	 */
	protected function addPluginMenuPage($icon = 'admin-plugins')
	{
		if ( !Stringify::contains($icon, 'http') ) {
			$icon = "dashicons-{$icon}";
		}
		$prefix = Stringify::replace('-', '_', $this->getNameSpace());
		return $this->addMenuPage(
			$this->translateString("{$this->getPluginName()} Dashboard"),
			$this->getPluginName(),
			"manage_{$prefix}",
			$this->getNameSpace(),
			[$this,'index'],
			$icon
		);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @access protected
	 * @param string callable
	 * @return {inherit}
	 */
	protected function addPluginSubMenuPage($callable, $slug, $title = '', $parent = null)
	{
		$slug = "{$this->getNameSpace()}-{$slug}";
		$menu = $this->translateString("{$this->getPluginName()} Dashboard");
		if ( empty($title) ) {
			$title = $this->getPluginName();
			$menu = $this->translateString("{$this->getPluginName()} {$title}");
		}
		if ( $parent == 'this' ) {
			$parent = $this->getNameSpace();
		}
		$prefix = Stringify::replace('-', '_', $this->getNameSpace());
		$capability = "manage_{$prefix}";
		return $this->addSubMenuPage($parent, $title, $title, $capability, $slug, [$this, $callable]);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param {inherit}
	 * @return {inherit}
	 */
	protected function addPluginJS($path, $deps = [], $version = false, $footer = true)
	{
		$id = Stringify::replace('.js', '', basename($path));
		$id = Stringify::replace('.min', '', $id);
		$path = "{$this->getAsset()}{$path}";
		$this->addJS("{$this->getNameSpace()}-{$id}",$path,$deps,$version,$footer);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param {inherit}
	 * @return {inherit}
	 */
	protected function addPluginMainJS($path, $deps = [], $version = false, $footer = true)
	{
		$path = "{$this->getAsset()}{$path}";
		$this->addJS("{$this->getNameSpace()}-main",$path,$deps,$version,$footer);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param {inherit}
	 * @return {inherit}
	 */
	protected function addPluginGlobalJS($path, $deps = [], $version = false, $footer = true)
	{
		$path = "{$this->getAsset()}{$path}";
		$this->addJS("{$this->getNameSpace()}-global",$path,$deps,$version,$footer);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return {inherit}
	 */
	protected function localizePluginJS($content = [], $id = 'main')
	{
		$prefix = Stringify::replace('-', '', $this->getNameSpace());
		$object = "{$prefix}Plugin";
		$this->localizeJS("{$this->getNameSpace()}-{$id}",$object,$content);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return {inherit}
	 */
	protected function localizeGlobalJS($content = [], $id = 'global')
	{
		$prefix = Stringify::replace('-', '', $this->getNameSpace());
		$object = "{$prefix}Global";
		$this->localizeJS("{$this->getNameSpace()}-{$id}",$object,$content);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return {inherit}
	 */
	protected function addPluginCSS($path, $deps = [], $version = false, $media = 'all')
	{
		$id = Stringify::replace('.css', '', basename($path));
		$id = Stringify::replace('.min', '', $id);
		$path = "{$this->getAsset()}{$path}";
		$this->addCSS("{$this->getNameSpace()}-{$id}",$path,$deps,$version,$media);
	}

	/**
	 * Add Plugin Main CSS
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return {inherit}
	 */
	protected function addPluginMainCSS($path, $deps = [], $version = false, $media = 'all')
	{
		$path = "{$this->getAsset()}{$path}";
		$this->addCSS("{$this->getNameSpace()}-main",$path,$deps,$version,$media);
	}

	/**
	 * Add Plugin Global CSS
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param {inherit}
	 * @return {inherit}
	 */
	protected function addPluginGlobalCSS($path, $deps = [], $version = false, $media = 'all')
	{
		$path = "{$this->getAsset()}{$path}";
		$this->addCSS("{$this->getNameSpace()}-global",$path,$deps,$version,$media);
	}

	/**
	 * Retrieves an option value based on an option name
	 *
	 * @see /reference/functions/get_transient/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return mixed
	 */
	protected function getTransient($name)
	{
		return get_transient("{$this->getNameSpace()}-{$name}");
	}

	/**
	 * Retrieves an option value based on an option name
	 *
	 * @see /reference/functions/set_transient/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @param mixed $value
	 * @param int $expiration
	 * @return mixed
	 */
	protected function setTransient($name, $value, $expiration = 300)
	{
		return set_transient("{$this->getNameSpace()}-{$name}",$value,$expiration);
	}

	/**
	 * Retrieves an option value based on an option name
	 *
	 * @see /reference/functions/set_transient/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return mixed
	 */
	protected function deleteTransient($name)
	{
		delete_transient("{$this->getNameSpace()}-{$name}");
	}

	/**
	 * Check if is plugin namespace
	 *
	 * @param string $slug null
	 * @return boolean
	 */
	protected function isPluginAdmin($slug = null)
	{
		$protocol = Server::getProtocol();
		$host = Server::get('HTTP_HOST');
		$request = Server::get('REQUEST_URI');
		$url = "{$protocol}{$host}{$request}";
		$current = ($slug) ? $slug : "?page={$this->getNameSpace()}";
		if ( Stringify::contains($url, $current) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get current used lang
	 *
	 * @param boolean $local
	 * @return string
	 */
	protected function getLanguage($local = false)
	{
		$lang = get_locale();
		if ($local) {
			return $lang;
		} else {
			return substr($lang, 0, strpos($lang, '_'));
		}
	}

	/**
	 * Return plugin infos
	 *
	 * @param string $file {pluginDir}/{pluginMain}.php
	 * @return array
	 */
	protected function getPluginInfo($file)
	{
		return get_plugin_data($file);
	}

	/**
	 * Return plugin active
	 *
	 * @param string $file {pluginDir}/{pluginMain}.php
	 * @return boolean
	 */
	protected function isPlugin($file)
	{
		if ( function_exists('is_plugin_active') ) {
			return is_plugin_active($file);
		} else {
			$plugins = $this->applyFilter('active_plugins', $this->getOption('active_plugins'));
			return in_array($file, $plugins);
		}
		return false;
	}

	/**
	 * Return class exists
	 *
	 * @param string $callable
	 * @return array
	 */
	protected function isClass($callable)
	{
		$callable = Stringify::replace('/', '\\', $callable);
		if ( Stringify::contains($callable, '\\') ) {
			if ( !File::exists( Stringify::formatPath(WP_PLUGIN_DIR."{$callable}.php") ) ) {
				return false;
			}
		}
		return class_exists($callable);
	}

	/**
	 * Return function exists
	 *
	 * @param string $callable
	 * @return array
	 */
	protected function isFunction($callable)
	{
		return function_exists($callable);
	}

	/**
	 * Loads a plugin’s translated strings
	 *
	 * @param void
	 * @return void
	 *
	 * action : plugins_loaded
	 */
	public function translate()
	{
		load_plugin_textdomain($this->getNameSpace(), false, "{$this->getNameSpace()}/languages");
	}

	/**
	 * Translated string
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public function translateString($string = '')
	{
		return __($string, $this->getNameSpace());
	}

	/**
	 * Translated string
	 *
	 * @access public
	 * @param string $string
	 * @param mixed $var
	 * @return string
	 */
	public function translateVar($string = '', $var = null)
	{
		$var = preg_replace('/\s+/', $this->translateString('{Empty}'), $var);
		return sprintf( $this->translateString(Stringify::replace($var, '%s', $string)), $var);
	}

	/**
	 * Add PLugin Cap
	 *
	 * @access protected
	 * @param string $role
	 * @param string $cap
	 * @return {inherit}
	 */
	protected function addPluginCapability($role, $cap)
	{
		$prefix = Stringify::replace('-', '_', $this->getNameSpace());
		$this->addCapability($role, "{$cap}_{$prefix}");
	}

	/**
	 * Remove PLugin Cap
	 *
	 * @access protected
	 * @param string $role
	 * @param string $cap
	 * @return {inherit}
	 */
	protected static function removePluginCapability($role, $cap)
	{
		$plugin = self::getStatic();
		$prefix = Stringify::replace('-', '_', $plugin->getNameSpace());
		parent::removeCapability($role, "{$cap}_{$prefix}");
	}

	/**
	 * Check token
	 *
	 * @access public
	 * @param int|string $action
	 * @return boolean
	 */
	public function checkToken($action = -1)
	{
		$nonce = Post::isSetted('nonce') ? Post::get('nonce') : false;
		if ( !$nonce ) {
			$nonce = Get::isSetted('nonce') ? Get::get('nonce') : false;
		}
		if ( !wp_verify_nonce($nonce, $action) ) {
			die($this->translateString('Invalid token'));
		}
	}

	/**
	 * Simple save action
	 *
	 * @access protected
	 * @param void
	 * @return boolean
	 */
	protected function saved()
	{
		if ( Get::isSetted('settings-updated') 
		&& Get::get('settings-updated') == 'true' ) {
			return true;
		}
	}
}
