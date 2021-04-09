<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.6
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\VanillePluginConfig;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\inc\HttpRequest;
use VanillePlugin\inc\HttpGet;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Server;
use VanillePlugin\thirdparty\Translator;

class PluginOptions extends WordPress
{
	use VanillePluginConfig;

	/**
	 * Fire plugin action
	 *
	 * @access protected
	 * @param string $hook
	 * @param array $args null
	 * @return mixed
	 */
	protected function doPluginAction($hook, $args = null)
	{
		$this->doAction("{$this->getNameSpace()}-{$hook}",$args);
	}

	/**
	 * Add plugin action
	 *
	 * @access protected
	 * @param string $hook
	 * @param array $args null
	 * @return mixed
	 */
	protected function addPluginAction($hook, $args = null)
	{
		return $this->addAction("{$this->getNameSpace()}-{$hook}",$args);
	}

	/**
	 * Add plugin filter
	 *
	 * @access protected
	 * @param string $hook
	 * @param array $args null
	 * @return mixed
	 */
	protected function addPluginFilter($hook, $args = null)
	{
		return $this->addFilter("{$this->getNameSpace()}-{$hook}",$args);
	}

	/**
	 * Apply plugin filter
	 *
	 * @access protected
	 * @param string $hook
	 * @param mixed $value
	 * @param array $args null
	 * @return mixed
	 */
	protected function applyPluginFilter($hook, $value, $args = null)
	{
		return $this->applyFilter("{$this->getNameSpace()}-{$hook}",$value,$args);
	}

	/**
	 * Has plugin filter
	 *
	 * @access protected
	 * @param string $hook
	 * @param mixed $method
	 * @return mixed
	 */
	protected function hasPluginFilter($hook, $method)
	{
		return $this->hasFilter("{$this->getNameSpace()}-{$hook}",$method);
	}

	/**
	 * Register plugin settings
	 *
	 * @access protected
	 * @param string $group
	 * @param mixed $option
	 * @param array $args
	 * @param mixed $lang
	 * @return void
	 */
	protected function registerPluginOption($group, $option, $args = null, $lang = null)
	{
		// Define multilingual
		$lang = $this->setOptionLanguage($lang);
		$this->registerOption("{$this->getPrefix()}{$group}","{$this->getPrefix()}{$option}{$lang}",$args);
	}

	/**
	 * Addd plugin option
	 *
	 * @access protected
	 * @param string $option
	 * @param mixed $value
	 * @param mixed $lang
	 * @return mixed
	 */
	protected function addPluginOption($option, $value, $lang = null)
	{
		// Define multilingual
		$lang = $this->setOptionLanguage($lang);
		return $this->addOption("{$this->getPrefix()}{$option}{$lang}",$value);
	}

	/**
	 * Get plugin option
	 *
	 * @access protected
	 * @param string $option
	 * @param string $type
	 * @param bool $default
	 * @param mixed $lang
	 * @return mixed
	 */
	protected function getPluginOption($option, $type = 'array', $default = false, $lang = null)
	{
		// Define multilingual
		$lang = $this->setOptionLanguage($lang);
		$value = $this->getOption("{$this->getPrefix()}{$option}{$lang}",$default);
		switch ($type) {
			case 'int':
			case 'integer':
				return intval($value);
				break;

			case 'double':
			case 'float':
				return floatval($value);
				break;

			case 'bool':
			case 'boolean':
				return boolval($value);
				break;
		}
		return $value;
	}

	/**
	 * Update plugin option
	 *
	 * @access protected
	 * @param string $option
	 * @param mixed $value
	 * @param mixed $lang
	 * @return bool
	 */
	protected function updatePluginOption($option, $value, $lang = null)
	{
		// Define multilingual
		$lang = $this->setOptionLanguage($lang);
		return $this->updateOption("{$this->getPrefix()}{$option}{$lang}",$value);
	}

	/**
	 * Remove plugin option
	 *
	 * @access protected
	 * @param string $option
	 * @param mixed $lang
	 * @return bool
	 */
	protected function removePluginOption($option, $lang = null)
	{
		// Define multilingual
		$lang = $this->setOptionLanguage($lang);
		return $this->removeOption("{$this->getPrefix()}{$option}{$lang}");
	}

	/**
	 * Remove all plugin options
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function removePluginOptions()
	{
		$db = new Orm();
		$query = "DELETE FROM {$db->prefix}options WHERE `option_name` LIKE '%{$this->getNameSpace()}_%'";
		$db->query($query);
	}

	/**
	 * Retrieves plugin option as object
	 *
	 * @access protected
	 * @param string $option
	 * @return object
	 */
	protected function getPluginObject($option, $lang = null)
	{
		// Define multilingual
		$lang = $this->setOptionLanguage($lang);
		return Stringify::toObject($this->getPluginOption($option,'array',[],$lang));
	}

	/**
	 * Add plugin menu page
	 *
	 * @access protected
	 * @param array $settings
	 * @return string
	 */
	protected function addPluginMenuPage($settings = [])
	{
		// Set title 
		$title = isset($settings['title']) ? $settings['title'] : '';
		if ( empty($title) ) {
			$title = $this->translateString("{$this->getPluginName()} Dashboard");
		}
		// Set menu 
		$menu = isset($settings['menu']) ? $settings['menu'] : '';
		if ( empty($menu) ) {
			$menu = $this->getPluginName();
		}
		// Set capability
		$cap = isset($settings['capability']) ? $settings['capability'] : '';
		if ( empty($cap) ) {
			$prefix = Stringify::replace('-','_',$this->getNameSpace());
			$cap = "manage_{$prefix}";
		}
		// Set slug
		$slug = isset($settings['slug']) ? $settings['slug'] : '';
		if ( empty($slug) ) {
			$slug = $this->getNameSpace();
		}
		// Set callback 
		$callback = isset($settings['callback']) ? $settings['callback'] : '';
		if ( empty($callback) ) {
			$callback = [$this,'index'];
		}
		// Set icon
		$icon = isset($settings['icon']) ? $settings['icon'] : 'admin-plugins';
		if ( !empty($icon) && !Stringify::contains($icon,'http') ) {
			$icon = "dashicons-{$icon}";
		}
		return $this->addMenuPage($title,$menu,$cap,$slug,$callback,$icon);
	}

	/**
	 * Add plugin submenu page
	 *
	 * @access protected
	 * @param array $settings
	 * @return mixed
	 */
	protected function addPluginSubMenuPage($settings = [])
	{
		// Set parent 
		$parent = isset($settings['parent']) ? $settings['parent'] : '';
		if ( empty($parent) ) {
			$parent = $this->getNameSpace();
		}
		// Set title 
		$title = isset($settings['title']) ? $settings['title'] : '';
		if ( empty($title) ) {
			$title = $this->translateString("{$this->getPluginName()} Dashboard");
		}
		// Set menu 
		$menu = isset($settings['menu']) ? $this->translateString($settings['menu']) : '';
		if ( empty($menu) ) {
			$menu = $this->getPluginName();
		}
		// Set icon
		if ( isset($settings['icon']) && !empty($settings['icon']) ) {
			$menu = "{$settings['icon']} {$menu}";
		}
		// Set capability
		$cap = isset($settings['capability']) ? $settings['capability'] : '';
		if ( empty($cap) ) {
			$prefix = Stringify::replace('-','_',$this->getNameSpace());
			$cap = "manage_{$prefix}";
		}
		// Set slug
		$slug = isset($settings['slug']) ? "{$this->getNameSpace()}-{$settings['slug']}" : $this->getNameSpace();
		// Set callback 
		$callback = isset($settings['callback']) ? $settings['callback'] : '';
		if ( empty($callback) ) {
			$callback = [$this,'index'];
		}
		return $this->addSubMenuPage($parent,$title,$menu,$cap,$slug,$callback);
	}

	/**
	 * Reset plugin submenu
	 *
	 * @access protected
	 * @param string $title
	 * @param string $icon
	 * @return void
	 */
	protected function resetPluginSubMenu($title = null, $icon = null)
	{
		global $submenu;
		if ( isset($submenu[$this->getNameSpace()]) ) {
			if ( $title ) {
				$title = $this->translateString($title);
				if ( $icon ) {
					$title = "{$icon} {$title}";
				}
				$submenu[$this->getNameSpace()][0][0] = $title;
			} else {
				unset($submenu[$this->getNameSpace()][0]);
			}
		}
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @access protected
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $footer true
	 * @return void
	 */
	protected function addPluginJS($path, $deps = [], $version = false, $footer = true)
	{
		$id = Stringify::replace('.js','',basename($path));
		$id = Stringify::replace('.min','',$id);
		$path = "{$this->getAsset()}{$path}";
		$this->addJS("{$this->getNameSpace()}-{$id}",$path,$deps,$version,$footer);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @access protected
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $footer true
	 * @return void
	 */
	protected function addPluginMainJS($path, $deps = [], $version = false, $footer = true)
	{
		$path = "{$this->getAsset()}{$path}";
		$this->addJS("{$this->getNameSpace()}-main",$path,$deps,$version,$footer);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @access protected
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $footer true
	 * @return void
	 */
	protected function addPluginGlobalJS($path, $deps = [], $version = false, $footer = true)
	{
		$path = "{$this->getAsset()}{$path}";
		$this->addJS("{$this->getNameSpace()}-global",$path,$deps,$version,$footer);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @access protected
	 * @param array $name
	 * @param string $id
	 * @return void
	 */
	protected function localizePluginJS($content = [], $id = 'main')
	{
		$prefix = Stringify::replace('-','',$this->getNameSpace());
		$object = "{$prefix}Plugin";
		$this->localizeJS("{$this->getNameSpace()}-{$id}",$object,$content);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @access protected
	 * @param array $name
	 * @param string $id
	 * @return void
	 */
	protected function localizeGlobalJS($content = [], $id = 'global')
	{
		$prefix = Stringify::replace('-','',$this->getNameSpace());
		$object = "{$prefix}Global";
		$this->localizeJS("{$this->getNameSpace()}-{$id}",$object,$content);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @access protected
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $media
	 * @return void
	 */
	protected function addPluginCSS($path, $deps = [], $version = false, $media = 'all')
	{
		$id = Stringify::replace('.css','',basename($path));
		$id = Stringify::replace('.min','',$id);
		$path = "{$this->getAsset()}{$path}";
		$this->addCSS("{$this->getNameSpace()}-{$id}",$path,$deps,$version,$media);
	}

	/**
	 * Add Plugin Main CSS
	 *
	 * @access protected
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $media
	 * @return void
	 */
	protected function addPluginMainCSS($path, $deps = [], $version = false, $media = 'all')
	{
		$path = "{$this->getAsset()}{$path}";
		$this->addCSS("{$this->getNameSpace()}-main",$path,$deps,$version,$media);
	}

	/**
	 * Add Plugin Global CSS
	 *
	 * @access protected
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $media
	 * @return void
	 */
	protected function addPluginGlobalCSS($path, $deps = [], $version = false, $media = 'all')
	{
		$path = "{$this->getAsset()}{$path}";
		$this->addCSS("{$this->getNameSpace()}-global",$path,$deps,$version,$media);
	}

	/**
	 * Retrieves the value of a transient
	 *
	 * @see /reference/functions/get_transient/
	 * @access protected
	 * @param string $key
	 * @return mixed
	 */
	protected function getTransient($key)
	{
		$key = substr(Stringify::slugify("{$this->getNameSpace()}-{$key}"), 0, 172);
		return get_transient($key);
	}

	/**
	 * Set the value of a transient
	 *
	 * @see /reference/functions/set_transient/
	 * @access protected
	 * @param string $key
	 * @param mixed $value
	 * @param int $expiration
	 * @return bool
	 */
	protected function setTransient($key, $value = 1, $expiration = 300)
	{
		$key = substr(Stringify::slugify("{$this->getNameSpace()}-{$key}"), 0, 172);
		return set_transient($key,$value,$expiration);
	}

	/**
	 * Deletes a transient
	 *
	 * @see /reference/functions/delete_transient/
	 * @access protected
	 * @param string $key
	 * @return void
	 */
	protected function deleteTransient($key)
	{
		$key = substr(Stringify::slugify("{$this->getNameSpace()}-{$key}"), 0, 172);
		delete_transient($key);
	}

	/**
	 * Deletes all transients
	 *
	 * @access protected
	 * @param void
	 * @return void
	 */
	protected function deleteTransients()
	{
		$db = new Orm();
		$query = "DELETE FROM {$db->prefix}options WHERE `option_name` LIKE '%_transient_{$this->getNameSpace()}_%'";
		$db->query($query);
	}

	/**
	 * Check if is plugin namespace
	 *
	 * @access protected
	 * @param string $slug null
	 * @return bool
	 */
	protected function isPluginAdmin($slug = null)
	{
		$protocol = Server::getProtocol();
		$host = Server::get('HTTP_HOST');
		$request = Server::get('REQUEST_URI');
		$url = "{$protocol}{$host}{$request}";
		$current = ($slug) ? $slug : "?page={$this->getNameSpace()}";
		if ( Stringify::contains($url,$current) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get current used local
	 *
	 * @access protected
	 * @param bool $local
	 * @return string
	 */
	protected function getLocale($local = false)
	{
		$lang = get_user_locale();
		if ($local) {
			return $lang;
		} else {
			return substr($lang,0,strpos($lang,'_'));
		}
	}

	/**
	 * Get current used lang
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getLanguage()
	{
		if ( $this->hasMultilingual() ) {
			$lang = Translator::getCurrentLanguage();
		} else {
			$lang = $this->getLocale();
		}
		return ($lang) ? $lang : $this->getLocale();
	}

	/**
	 * Return plugin infos via absolute path
	 *
	 * @access protected
	 * @param string $file
	 * @return array
	 */
	protected function getPluginInfo($file)
	{
		return get_plugin_data($file);
	}

	/**
	 * Return plugin active
	 *
	 * @access protected
	 * @param string $file {pluginDir}/{pluginMain}.php
	 * @return bool
	 */
	protected function isPlugin($file)
	{
		if ( function_exists('is_plugin_active') ) {
			return is_plugin_active($file);
		} else {
			$plugins = $this->applyFilter('active_plugins',$this->getOption('active_plugins'));
			return in_array($file,$plugins);
		}
		return false;
	}

	/**
	 * Return class exists
	 *
	 * @access protected
	 * @param string $callable
	 * @return bool
	 */
	protected function isClass($callable)
	{
		$callable = Stringify::replace('/','\\',$callable);
		if ( Stringify::contains($callable,'\\') ) {
			if ( !File::exists($this->getPluginDir("{$callable}.php")) ) {
				return false;
			}
		}
		return class_exists($callable);
	}

	/**
	 * Return function exists
	 *
	 * @access protected
	 * @param string $callable
	 * @return bool
	 */
	protected function isFunction($callable)
	{
		return function_exists($callable);
	}

	/**
	 * Add Plugin Cap
	 *
	 * @access protected
	 * @param string $role
	 * @param string $cap
	 * @return void
	 */
	protected function addPluginCapability($role, $cap)
	{
		$prefix = Stringify::replace('-','_',$this->getNameSpace());
		$this->addCapability($role,"{$cap}_{$prefix}");
	}

	/**
	 * Check Plugin Cap
	 *
	 * @access protected
	 * @param string $cap
	 * @return void
	 */
	protected function hadPluginCapability($cap)
	{
		$prefix = Stringify::replace('-','_',$this->getNameSpace());
		$this->hadCapability("{$cap}_{$prefix}");
	}

	/**
	 * Remove Plugin Cap
	 *
	 * @access protected
	 * @param string $role
	 * @param string $cap
	 * @return void
	 */
	protected function removePluginCapability($role, $cap)
	{
		$prefix = Stringify::replace('-','_',$this->getNameSpace());
		$this->removeCapability($role,"{$cap}_{$prefix}");
	}

	/**
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function isGutenberg()
	{
		$gutenberg = false;
		$classic = false;
		if ( $this->hasFilter('replace_editor','gutenberg_init') ) {
			$gutenberg = true;
		}
		if ( $this->versionCompare($GLOBALS['wp_version'],'5.0-beta','>') ) {
			$classic = true;
		}
		if ( !$gutenberg && !$classic ) {
			return false;
		}
		if ( !$this->isPlugin('classic-editor/classic-editor.php') ) {
			return true;
		}
		return ($this->getOption('classic-editor-replace') === 'no-replace');
	}

	/**
	 * @access protected
	 * @param string $haystack
	 * @param mixed $needle
	 * @return bool
	 */
	protected function hasScript($haystack, $needle)
	{
	    if ( !TypeCheck::isArray($needle) ) {
	    	$needle = [$needle];
	    }
	    foreach ($needle as $search) {
	        if ( Stringify::contains($haystack,$search) ) {
	        	return true;
	        }
	    }
	    return false;
	}

	/**
	 * Simple save action
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function saved()
	{
		if ( HttpGet::isSetted('settings-updated') 
		&& HttpGet::get('settings-updated') == 'true' ) {
			return true;
		}
	}
	
	/**
	 * Compare Versions
	 *
	 * @access public
	 * @param string $version1
	 * @param string $version2
	 * @param string $operator
	 * @return bool
	 */
	public function versionCompare($version1, $version2, $operator = '==')
	{
		return version_compare($version1,$version2,$operator);
	}

	/**
	 * Load plugin translated strings
	 *
	 * @access public
	 * @param void
	 * @return void
	 *
	 * Action : init
	 */
	public function translate()
	{
		// Set overriding path
		$override = "{$this->getThemeDir()}/{$this->getNameSpace()}/languages";
		$override = $this->applyPluginFilter('override-translate-path', $override);
        if ( File::isDir($override) ) {
        	$override .= sprintf('/%1$s-%2$s.mo',$this->getNameSpace(),$this->getLocale(true));
            load_textdomain($this->getNameSpace(),$override);
        } else {
        	load_plugin_textdomain($this->getNameSpace(), false, "{$this->getNameSpace()}/languages");
        }
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
		if ( TypeCheck::isArray($var) ) {
			return vsprintf($this->translateString(Stringify::replaceArray($var,$string)), $var);
		} else {
			$var = Stringify::replaceRegex('/\s+/', $this->translateString('{Empty}'), $var);
			return sprintf($this->translateString(Stringify::replace($var,'%s',$string)), $var);
		}
	}

	/**
	 * Get current screen
	 *
	 * @access public
	 * @param void
	 * @return object
	 */
	public function getCurrentScreen()
	{
		return get_current_screen();
	}

	/**
	 * Check is current screen
	 *
	 * @access public
	 * @param string $screen
	 * @return bool
	 */
	public function isCurrentScreen($screen = null)
	{
		$screen = ($screen) ? $screen : "toplevel_page_{$this->getNameSpace()}";
		$current = $this->getCurrentScreen();
		if ( $current->base == $screen ) {
			return true;
		}
		return false;
	}

	/**
	 * Add help tab
	 *
	 * @access public
	 * @param array $settings
	 * @return void
	 */
	public function addHelpTab($settings)
	{
		$this->getCurrentScreen()->add_help_tab($settings);
	}

	/**
	 * Set help sidebar
	 *
	 * @access public
	 * @param string $html
	 * @return void
	 */
	public function setHelpSidebar($html)
	{
		$this->getCurrentScreen()->set_help_sidebar($html);
	}

	/**
	 * Set help sidebar
	 *
	 * @access public
	 * @param object $bar
	 * @param array $settings
	 * @return void
	 */
	public function addMenu($bar, $settings = [])
	{
		$bar->add_menu($settings);
	}

	/**
	 * Check token
	 *
	 * @access public
	 * @param int|string $action
	 * @return bool
	 */
	public function checkToken($action = -1)
	{
		$nonce = HttpRequest::isSetted('nonce') ? HttpRequest::get('nonce') : false;
	    if ( !$this->checkNonce($nonce,$action) ) {
	    	die($this->translateString('Invalid token'));
	    }
	}

	/**
	 * Check nonce
	 *
	 * @access public
	 * @param string $nonce
	 * @param int|string $action
	 * @return bool
	 */
	public function checkNonce($nonce = '', $action = -1)
	{
	  	return wp_verify_nonce($nonce,$action);
	}

	/**
	 * Create nonce
	 *
	 * @access public
	 * @param int|string $action
	 * @return string
	 */
	public function createNonce($action = -1)
	{
	  	return wp_create_nonce($action);
	}

	/**
	 * Check Ajax Referer
	 *
	 * @access public
	 * @param int|string $action
	 * @param false|string $args
	 * @param bool $die
	 * @return int|false
	 */
	public function checkAjaxReferer($action = -1, $args = false, $die = true)
	{
	  	return check_ajax_referer($action,$args,$die);
	}

	/**
	 * Get multilingual status
	 *
	 * @access public
	 * @param void
	 * @return bool
	 */
	public function hasMultilingual()
	{
		if ( $this->isMultilingual() ) {
			if ( Translator::isActive() ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Set options language
	 *
	 * @access private
	 * @param mixed $lang
	 * @return mixed
	 */
	private function setOptionLanguage($lang = null)
	{
		if ( $this->hasMultilingual() ) {
			if ( $lang !== false ) {
				$lang = "-{$this->getLanguage()}";
			}
		}
		return $lang;
	}
}
