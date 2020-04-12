<?php
/**
 * WordPress Class Wrapper
 * @see https://developer.wordpress.org/
 *
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 * Allowed to edit for plugin customization
 */

namespace VanillePlugin\lib;

use VanilleNameSpace\core\system\includes\Config;

class WordPress
{
	/**
	 * Register a shortcode handler
	 *
	 * @see /reference/functions/add_shortcode/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $tag, Tag name
	 * @param array $method, callable
	 * @return void
	 */
	protected function addShortcode($tag, $method)
	{
		add_shortcode($tag, $method);
	}

	/**
	 * Search content for shortcodes 
	 * and filter shortcodes through their hooks
	 *
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param inherit
	 * @param inherit
	 * @return void
	 */
	protected function renderShortcode($content, $ignore = false)
	{
		echo $this->doShortcode($content, $ignore);
	}

	/**
	 * Search content for shortcodes 
	 * and filter shortcodes through their hooks
	 *
	 * @see /reference/functions/do_shortcode/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $content
	 * @param boolean $ignore, Ignore HTML
	 * @return string
	 */
	protected function doShortcode($content, $ignore = false)
	{
		return do_shortcode($content, $ignore);
	}

	/**
	 * Removes hook for shortcode
	 *
	 * @see /reference/functions/remove_shortcode/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $tag, Tag name
	 * @return void
	 */
	protected function removeShortcode($tag)
	{
		remove_shortcode($tag);
	}

	/**
	 * Checks Whether a registered shortcode exists named $tag
	 *
	 * @see /reference/functions/shortcode_exists/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $tag, Tag name
	 * @return boolean
	 */
	protected function shortcodeExist($tag)
	{
		return shortcode_exists($tag);
	}

	/**
	 * Checks whether content contains shortcode
	 *
	 * @see /reference/functions/has_shortcode/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $content
	 * @param string $tag
	 * @return boolean
	 */
	protected function shortcodeIn($content,$tag)
	{
		return has_shortcode($content,$tag);
	}

	/**
	 * Hook a method on a specific action
	 *
	 * @see /reference/functions/add_action/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $hook
	 * @param callable $method
	 * @param int $priority default 10
	 * @param int $args default 1
	 * @return true
	 */
	protected function addAction($hook, $method, $priority = 10, $args = 1)
	{
		switch ($hook)
		{
			case 'head':
				return add_action('wp_head',$method,$priority,$args);
				break;
			case 'footer':
				return add_action('wp_footer',$method,$priority,$args);
				break;
			default:
				return add_action($hook,$method,$priority,$args);
				break;
		}
	}

	/**
	 * Remove a method from a specified action hook
	 *
	 * @see /reference/functions/remove_action/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $target
	 * @param callable $method
	 * @param int $priority default 10
	 * @return boolean
	 */
	protected function removeAction($target, $method, $priority = 10)
	{
		switch ($target)
		{
			case 'head':
				return remove_action('wp_head',$method,$priority);
				break;
			case 'footer':
				return remove_action('wp_footer',$method,$priority);
				break;
			default:
				return remove_action($target,$method,$priority);
				break;
		}
	}

	/**
	 * Add a method from a specified action hook
	 *
	 * @see /reference/functions/do_action/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $tag
	 * @param mixed $args
	 * @return void
	 */
	protected function doAction($tag, $args = '')
	{
		do_action($tag,$args);
	}

	/**
	 * Hook a function or method to a specific filter action
	 *
	 * @see /reference/functions/add_filter/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $hook
	 * @param callable $method
	 * @param int $priority default 10
	 * @param int $args default 1
	 * @return true
	 */
	protected function addFilter($hook, $method, $priority = 10, $args = 1)
	{
		return add_filter($hook,$method,$priority,$args);
	}

	/**
	 * Remove a function from a specified filter hook
	 *
	 * @see /reference/functions/remove_filter/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $hook
	 * @param callable $method
	 * @param int $priority default 10
	 * @return boolean
	 */
	protected function removeFilter($hook, $method, $priority = 10)
	{
		remove_filter($hook,$method,$priority,$args);
	}

	/**
	 * Calls the callback functions 
	 * that have been added to a filter hook
	 *
	 * @see /reference/functions/apply_filters/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $hook
	 * @param mixed $value
	 * @return mixed
	 */
	protected function applyFilter($hook, $value)
	{
		return apply_filters($hook,$value);
	}

	/**
	 * Check if any filter has been registered for a hook
	 *
	 * @see /reference/functions/has_filter/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $tag
	 * @param callable $method
	 * @return mixed
	 */
	protected function hasFilter($tag, $method = false)
	{
		return has_filter($tag,$method);
	}

	/**
	 * Register and Enqueue a CSS stylesheet
	 *
	 * @see /reference/functions/wp_register_style/
	 * @see /reference/functions/wp_enqueue_style/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $id
	 * @param string $path
	 * @param array $deps
	 * @param string $version
	 * @param string $media
	 * @return void
	 */
	protected function addCSS($id, $path, $deps = [], $version = '', $media = 'all')
	{
		if ( strpos($path, 'http') == false ){
		    $path = $this->getPluginUrl($path);
		}
		wp_register_style("{$id}-css", $path, $deps, $version, $media);
		wp_enqueue_style("{$id}-css");
	}

	/**
	 * Register and Enqueue a new script
	 *
	 * @see /reference/functions/wp_register_script/
	 * @see /reference/functions/wp_enqueue_script/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $id
	 * @param string $path
	 * @param array $deps
	 * @param string $version
	 * @param string $footer
	 * @return void
	 */
	protected function addJS($id, $path, $deps = [], $version = false, $footer = false)
	{
		if ( strpos($path, 'http') == false ){
		    $path = $this->getPluginUrl($path);
		}
		wp_register_script("{$id}-js", $path, $deps, $version, $footer);
		wp_enqueue_script("{$id}-js");
	}

	/**
	 * Determines whether a script has been added to the queue
	 *
	 * @see /reference/functions/wp_register_script/
	 * @see /reference/functions/wp_enqueue_script/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $id
	 * @param string $list
	 * @return boolean
	 */
	protected function isJS($id, $list = 'enqueued')
	{
		if ( wp_script_is($id,$list)) {
			return true;
		}
		return false;
	}

	/**
	 * Remove a previously enqueued and registered CSS stylesheet
	 *
	 * @see /reference/functions/wp_dequeue_style/
	 * @see /reference/functions/wp_deregister_style/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $id 
	 * @return void
	 */
	protected function removeCSS($id)
	{
		wp_dequeue_style($id);
		wp_deregister_style($id);
	}

	/**
	 * Remove a previously enqueued and registered script
	 *
	 * @see /reference/functions/wp_dequeue_script/
	 * @see /reference/functions/wp_deregister_script/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $id 
	 * @return void
	 */
	protected function removeJS($id)
	{
		wp_dequeue_script($id);
		wp_deregister_script($id);
	}

	/**
	 * Localize a script
	 * Works on already added script only
	 *
	 * @see /reference/functions/wp_deregister_script/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $id
	 * @param object $object
	 * @param array $data
	 * @return boolean
	 */
	protected function localizeJS($id, $object, $data = [])
	{
		wp_localize_script("{$id}-js", $object, $data);
	}

	/**
	 * Set the activation hook for a plugin
	 *
	 * @see /reference/functions/register_activation_hook/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $file
	 * @param callable $method
	 * @return void
	 */
	protected function registerActivation($file, $method)
	{
		register_activation_hook($file, $method);
	}

	/**
	 * Set the deactivation hook for a plugin
	 *
	 * @see /reference/functions/register_deactivation_hook/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $file
	 * @param callable $method
	 * @return void
	 */
	protected function registerDeactivation($file, $method)
	{
		register_deactivation_hook($file, $method);
	}

	/**
	 * Set the uninstallation hook for a plugin
	 * use class name instead of $this
	 *
	 * @see /reference/functions/register_uninstall_hook/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $file
	 * @param callable $method
	 * @return void
	 */
	protected function registerUninstall($file, $method)
	{
		register_uninstall_hook($file,$method);
	}

	/**
	 * Register a settings and its data
	 *
	 * @see /reference/functions/register_setting/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $group
	 * @param string $name
	 * @param array $args
	 * @return void
	 */
	protected static function addOption($group, $name, $args = [])
	{
		register_setting($group,$name,$args);
	}

	/**
	 * Retrieves an option value based on an option name
	 *
	 * @see /reference/functions/get_option/
	 * @since 4.0.0
	 * @version 5.4
	 * @access protected
	 * @param string $name
	 * @param string $default
	 * @return mixed
	 */
	protected static function getOption($name, $default = null)
	{
		$option = get_option($name,$default);
		return Data::slashStrip($option);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @see /reference/functions/update_option/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name, mixed $value
	 * @return boolean
	 */
	protected static function updateOption($name, $value)
	{
		return update_option($name, $value);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @see /reference/functions/delete_option/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return boolean
	 */
	protected static function removeOption($name)
	{
		return delete_option($name);
	}

	/**
	 * Add a top-level menu page
	 *
	 * @see /reference/functions/add_menu_page/
	 * @since 4.0.0
	 * @access protected
	 * @param string $title
	 * @param string $menuTitle
	 * @param string $capability
	 * @param string $slug
	 * @param callable $method
	 * @param string $icon default dashicons-warning
	 * @param boolean $customIcon default false
	 * @param int $position default 20
	 * @return string
	 */
	protected function addMenuPage($title, $menuTitle, $capability, $slug, $method, $icon = 'admin-plugins',$customIcon = false, $position = 20)
	{
		if ($customIcon) $icon = "dashicons-{$icon}";
		return add_menu_page($title,$menuTitle,$capability,$slug,$method,$icon,$position);
	}

	/**
	 * Add a top-level menu page
	 *
	 * @see /reference/functions/add_menu_page/
	 * @since 4.0.0
	 * @access protected
	 * @param string $title, string $menuTitle, string $capability, string $slug, callable $method
	 * @return string
	 */
	protected function addSubMenuPage($parent, $pageTitle, $menuTitle, $capability, $slug, $method)
	{
		return add_submenu_page( $parent, $pageTitle, $menuTitle, $capability, $slug, $method);
	}

	/**
	 * Add a top-level menu page
	 *
	 * @see /reference/functions/add_menu_page/
	 * @since 4.0.0
	 * @access protected
	 * @param string $title, string $menuTitle, string $capability, string $slug, callable $method
	 * @return string
	 */
	protected function addOptionPage($pageTitle, $menuTitle, $capability, $slug, $method)
	{
		return add_options_page($pageTitle,$menuTitle,$capability,$slug,$method);
	}

	/**
	 * Add Metabox
	 *
	 * @access protected
	 * @param string $type, array $args
	 * @return boolean
	 *
	 * action : add_meta_boxes
	 * action : add_meta_boxes_{type}
	 */
	protected function addMetabox($id, $title, $callback, $screen, $context = 'advanced', $priority = 'high', $args = null)
	{
		add_meta_box($id, $title, $callback, $screen, $context, $priority, $args);
	}

	/**
	 * Clean Assets Url
	 *
	 * @access public
	 * @param string $url
	 * @return string
	 */
	public function cleanAssetsUrl($url)
	{
		if( strpos($url,'?ver=') ) $url = remove_query_arg('ver',$url);
		return $url;
	}

	/**
	 * Check if is plugin namespace
	 *
	 * @param void
	 * @return true|null
	 */
	public function isPluginAdmin()
	{
		$protocol = isset($_SERVER['HTTPS']) ? "https://" : "http://";
		$url = "{$protocol}{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

		// plugin namespace
		$namespace = Config::get('namespace');
		if ( strpos($url,"?page={$namespace}") !== false ) {
			return true;
		}
	}

	/**
	 * Get plugin root path
	 *
	 * @param null|string $path
	 * @return string
	 *
	 * @see plugin_dir_path
	 */
	protected function getRoot($path = null)
	{
		$path = isset($path) ? WP_PLUGIN_DIR . "/{$path}" : WP_PLUGIN_DIR;
		return "{$path}";
	}

	/**
	 * Retrieves a URL within the plugins or mu-plugins directory
	 *
	 * @param string $path
	 * @param string $plugin
	 * @return string
	 *
	 * @see plugin_dir_path
	 */
	protected function getPluginUrl($path = '', $plugin = '')
	{
		return plugins_url($path, $plugin);
	}

	/**
	 * Send notification
	 *
	 * @param void
	 * @return boolean
	 */
	protected function isAdmin($url = null)
	{
		if (is_admin($url)) return true;
	}

	/**
	 * Check user logged in
	 *
	 * @param void
	 * @return boolean
	 */
	protected function isLoggedIn()
	{
		if (is_user_logged_in()) return true;
	}

	/**
	 * Send email
	 *
	 * @param $to, $subject, $body, $header = []
	 * @return boolean
	 */
	protected function sendMail($to = null, $subject, $body, $header = null)
	{
		if ( is_null($header) ) $header = ['Content-Type:text/html;charset=UTF-8'];
		if ( is_null($to) || !$to ) $to = $this->getOption('admin_email');
		return wp_mail($to, $subject, $body, $header);
	}

	/**
	 * userExists
	 *
	 * @param $to, $subject, $body, $header = []
	 * @return boolean
	 */
	protected function userExists($email)
	{
		return email_exists($email);
	}

	/**
	 * Get user role
	 *
	 * @param void
	 * @return string | array
	 */
	protected function getRole($id = null)
	{
		if ( is_null($id) || empty($id) ) $id = get_current_user_id();
		$user = new \WP_User($id);
		if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
		    foreach ( $user->roles as $role )
		    echo $role;
		}
	}

	/**
	 * Get user role
	 *
	 * @param void
	 * @return object|null
	 */
	protected function addRole($role, $name = null, $capability = null)
	{
		if (is_null($name)) $name = $role;
		if (is_null($capability))
		{
			$capability = [

				'read' => true
			];
		}
		add_role($role,$name,$capability);
	}

	/**
	 * Remove user role
	 *
	 * @param string $role
	 * @return void
	 */
	protected static function removeRole($role)
	{
		remove_role($role);
	}

	/**
	 * Get user role
	 *
	 * @param void
	 * @return object|null
	 */
	protected function addCapability($role, $capability, $grant = true)
	{
		$role = get_role($role);
		$role->add_cap($capability,$grant);
	}

	/**
	 * Get user role
	 *
	 * @param void
	 * @return object|null
	 */
	protected static function removeCapability($role, $capability)
	{
		$role = get_role($role);
		$role->remove_cap($capability);
	}

	/**
	 * Loads a plugin’s translated strings
	 *
	 * @category Html
	 * @param void
	 * @return void
	 *
	 * action : after_setup_theme
	 */
	public function translate()
	{
		$namespace = Config::get('namespace');
		load_plugin_textdomain( $namespace, false, "{$namespace}/languages" ); 
	}

	/**
	 * Redirects to another page
	 *
	 * @category Http
	 * @param string $location, int $status
	 * @return void
	 */
	public function redirect($location, $status = 301)
	{
		wp_redirect($location, $status);
		exit();
	}

	/**
	 * Kill WordPress execution and display HTML message with error message
	 *
	 * @category System
	 * @param string $messsage, string $title, array $args
	 * @return void
	 */
	protected function except($message = '', $title = '', $args = [])
	{
		wp_die($message,$title,$args);
	}

	/**
	 * Kill WordPress execution and display HTML message with error message
	 *
	 * @category System
	 * @param string $messsage, string $title, array $args
	 * @return void
	 */
	protected function log($message = '')
	{
		error_log($message);
	}

	/**
	 * Return plugin infos
	 *
	 * @param string $name {pluginDir}/{pluginMain}.php
	 * @return void
	 */
	protected function pluginInfo($name)
	{
		return get_plugin_data("{$this->getRoot()}/{$name}");
	}
}
