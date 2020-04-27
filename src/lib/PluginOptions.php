<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\VanilleConfig;
use VanillePlugin\lib\WordPress;
use VanillePlugin\inc\Data;
use VanillePlugin\inc\Post;

class PluginOptions extends WordPress
{
	use VanilleConfig;

	/**
	 * Register a settings and its data
	 *
	 * @access protected
	 * @param inherit
	 * @return inherit
	 */
	protected function doPluginAction($action)
	{
		return $this->doAction("{$this->getNameSpace()}{$action}");
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
	protected function getPluginOption($option, $type = 'string')
	{
		$value = $this->getOption("{$this->getPrefix()}{$option}", false);
		switch ($type) {
			case 'string':
				return $value;
				break;
				
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
		return false;
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
		return Data::toObject( parent::getOption("{$this->getPrefix()}{$option}") );
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @see /reference/functions/delete_option/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return {inherit}
	 */
	protected function addPluginMenuPage($icon = 'admin-plugins')
	{
		return $this->addMenuPage(
			$this->translateString("{$this->getPluginName()} Dashboard"),
			$this->translateString($this->getPluginName()),
			"manage_{$this->getNameSpace()}",
			$this->getNameSpace(),
			[$this,'index'],
			"dashicons-{$icon}"
		);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param {inherit}
	 * @return {inherit}
	 */
	protected function addPluginJS($path, $deps = [], $version = false, $footer = false)
	{
		$id = str_replace('.js', '', basename($path));
		$id = str_replace('.min', '', $id);
		$path = "/{$this->getNameSpace()}{$this->getAsset()}{$path}";
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
	protected function addPluginMainJS($path, $deps = [], $version = false, $footer = false)
	{
		$path = "/{$this->getNameSpace()}{$this->getAsset()}{$path}";
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
	protected function addPluginGlobalJS($path, $deps = [], $version = false, $footer = false)
	{
		$path = "/{$this->getNameSpace()}{$this->getAsset()}{$path}";
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
		$object = "{$this->getNameSpace()}Plugin";
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
	protected function addPluginCSS($path, $deps = [], $version = '', $media = 'all')
	{
		$id = str_replace('.css', '', basename($path));
		$id = str_replace('.min', '', $id);
		$path = "/{$this->getNameSpace()}{$this->getAsset()}{$path}";
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
	protected function addPluginMainCSS($path, $deps = [], $version = '', $media = 'all')
	{
		$path = "/{$this->getNameSpace()}{$this->getAsset()}{$path}";
		echo $this->addCSS("{$this->getNameSpace()}-main",$path,$deps,$version,$media);
	}

	/**
	 * Add Plugin Global CSS
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param {inherit}
	 * @return {inherit}
	 */
	protected function addPluginGlobalCSS($path, $deps = [], $version = '', $media = 'all')
	{
		$path = "/{$this->getNameSpace()}{$this->getAsset()}{$path}";
		echo $this->addCSS("{$this->getNameSpace()}-global",$path,$deps,$version,$media);
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
		return get_transient("{$this->getNameSpace()}{$name}");
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
	protected function setTransient($name, $value, $expiration = 300 )
	{
		return set_transient("{$this->getNameSpace()}{$name}",$value,$expiration);
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
		delete_transient("{$this->getNameSpace()}{$name}");
	}

	/**
	 * Check if is plugin namespace
	 *
	 * @param void
	 * @return boolean
	 */
	protected function isPluginAdmin()
	{
		$protocol = isset($_SERVER['HTTPS']) ? "https://" : "http://";
		$url = "{$protocol}{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		if ( strpos($url,"?page={$this->getNameSpace()}") !== false ) {
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
	 * Loads a plugin’s translated strings
	 *
	 * @param void
	 * @return void
	 *
	 * action : plugins_loaded
	 */
	public function translate()
	{
		load_plugin_textdomain($this->getNameSpace(),false,"{$this->getNameSpace()}/languages");
	}

	/**
	 * Translated string
	 *
	 * @param void
	 * @return void
	 *
	 */
	public function translateString($string)
	{
		return __($string, $this->getNameSpace());
	}

	/**
	 * Loads a plugin’s translated strings
	 *
	 * @param void
	 * @return void
	 *
	 * action : after_setup_theme
	 */
	protected static function addPluginCapability($role, $cap)
	{
		parent::addCapability($role, "{$cap}_{$this->getNameSpace()}");
	}

	/**
	 * Loads a plugin’s translated strings
	 *
	 * @param void
	 * @return void
	 *
	 * action : after_setup_theme
	 */
	protected static function removePluginCapability($role, $cap)
	{
		parent::removeCapability($role, "{$cap}_{$this->getNameSpace()}");
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
		if ( !wp_verify_nonce( Post::get('nonce'), $action ) ) {
			die( $this->translateString('Invalid token') );
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

	/**
	 * @param void
	 * @return boolean
	 */
	protected static function isHttps()
	{
		if ( !empty($_SERVER['HTTPS']) 
		&& $_SERVER['HTTPS'] !== 'off' ) {
		    return true;
		}
	}
}
