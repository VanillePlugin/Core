<?php
/**
 * WordPress Class Wrapper
 * @see https://developer.wordpress.org/
 *
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.0
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\inc\Stringify;

/**
 * WordPress Class Wrrap Global Functions
 * Defines Only Base Function Used by Plugin
 */
class WordPress
{
	/**
	 * Set the activation hook for a plugin
	 * @see /reference/functions/register_activation_hook/
	 *
	 * @access protected
	 * @param string $file
	 * @param callable $method
	 * @return void
	 */
	protected function registerActivation($file, $method)
	{
		register_activation_hook($file,$method);
	}

	/**
	 * Set the deactivation hook for a plugin
	 * @see /reference/functions/register_deactivation_hook/
	 *
	 * @access protected
	 * @param string $file
	 * @param callable $method
	 * @return void
	 */
	protected function registerDeactivation($file, $method)
	{
		register_deactivation_hook($file,$method);
	}

	/**
	 * Set the uninstallation hook for a plugin
	 * use class name instead of $this
	 * @see /reference/functions/register_uninstall_hook/
	 *
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
	 * Register a shortcode handler
	 * @see /reference/functions/add_shortcode/
	 *
	 * @access protected
	 * @param string $tag
	 * @param callable $callback
	 * @return void
	 */
	protected function addShortcode($tag, $callback)
	{
		add_shortcode($tag,$callback);
	}

	/**
	 * Search content for shortcodes 
	 * and filter shortcodes through their hooks
	 *
	 * @access protected
	 * @param string $content
	 * @param boolean $ignore, Ignore HTML false
	 * @return void
	 */
	protected function renderShortcode($content, $ignore = false)
	{
		echo $this->doShortcode($content,$ignore);
	}

	/**
	 * Search content for shortcodes 
	 * and filter shortcodes through their hooks
	 * @see /reference/functions/do_shortcode/
	 *
	 * @access protected
	 * @param string $content
	 * @param boolean $ignore, Ignore HTML false
	 * @return string
	 */
	protected function doShortcode($content, $ignore = false)
	{
		return do_shortcode($content,$ignore);
	}

	/**
	 * Removes hook for shortcode
	 * @see /reference/functions/remove_shortcode/
	 *
	 * @access protected
	 * @param string $tag
	 * @return void
	 */
	protected function removeShortcode($tag)
	{
		remove_shortcode($tag);
	}

	/**
	 * Checks Whether a registered shortcode exists named $tag
	 * @see /reference/functions/shortcode_exists/
	 *
	 * @access protected
	 * @param string $tag
	 * @return boolean
	 */
	protected function shortcodeExists($tag)
	{
		return shortcode_exists($tag);
	}

	/**
	 * Checks whether content contains shortcode
	 * @see /reference/functions/has_shortcode/
	 *
	 * @access protected
	 * @param string $content
	 * @param string $tag
	 * @return boolean
	 */
	protected function shortcodeIn($content, $tag)
	{
		return has_shortcode($content,$tag);
	}

	/**
	 * Hook a method on a specific action
	 * @see /reference/functions/add_action/
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $method
	 * @param int $priority 10
	 * @param int $args 1
	 * @return inherit
	 */
	protected function addAction($hook, $method, $priority = 10, $args = 1)
	{
		switch ( Stringify::lowercase($hook) ) {
			case 'head':
				$hook = 'wp_head';
				break;
			case 'footer':
				$hook = 'wp_footer';
				break;
			case 'content':
				$hook = 'the_content';
				break;
		}
		return add_action($hook,$method,$priority,$args);
	}

	/**
	 * Remove a method from a specified action hook
	 * @see /reference/functions/remove_action/
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $method
	 * @param int $priority 10
	 * @return inherit
	 */
	protected function removeAction($hook, $method, $priority = 10)
	{
		switch ( Stringify::lowercase($hook) ) {
			case 'head':
				$hook = 'wp_head';
				break;
			case 'footer':
				$hook = 'wp_footer';
				break;
			case 'content':
				$hook = 'the_content';
				break;
		}
		return remove_action($hook,$method,$priority);
	}

	/**
	 * Add a method from a specified action hook
	 * @see /reference/functions/do_action/
	 *
	 * @access protected
	 * @param string $tag
	 * @param mixed $args
	 * @return void
	 */
	protected function doAction($tag, $args = null)
	{
		do_action($tag,$args);
	}

	/**
	 * Hook a function or method to a specific filter action
	 * @see /reference/functions/add_filter/
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $method
	 * @param int $priority 10
	 * @param int $args 1
	 * @return inherit
	 */
	protected function addFilter($hook, $method, $priority = 10, $args = 1)
	{
		switch ( Stringify::lowercase($hook) ) {
			case 'head':
				$hook = 'wp_head';
				break;
			case 'footer':
				$hook = 'wp_footer';
				break;
			case 'content':
				$hook = 'the_content';
				break;
		}
		return add_filter($hook,$method,$priority,$args);
	}

	/**
	 * Remove a function from a specified filter hook
	 * @see /reference/functions/remove_filter/
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $method
	 * @param int $priority 10
	 * @return inherit
	 */
	protected function removeFilter($hook, $method, $priority = 10)
	{
		return remove_filter($hook,$method,$priority);
	}

	/**
	 * Calls the callback functions 
	 * that have been added to a filter hook
	 * @see /reference/functions/apply_filters/
	 *
	 * @access protected
	 * @param string $hook
	 * @param mixed $value
	 * @param mixed $args null
	 * @return inherit
	 */
	protected function applyFilter($hook, $value, $args = null)
	{
		return apply_filters($hook,$value,$args);
	}

	/**
	 * Check if any filter has been registered for a hook
	 * @see /reference/functions/has_filter/
	 *
	 * @access protected
	 * @param string $tag
	 * @param callable $method false
	 * @return inherit
	 */
	protected function hasFilter($tag, $method = false)
	{
		return has_filter($tag,$method);
	}

	/**
	 * Register and Enqueue a CSS stylesheet
	 * @see /reference/functions/wp_register_style/
	 * @see /reference/functions/wp_enqueue_style/
	 *
	 * @access protected
	 * @param string $id
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $media
	 * @return void
	 */
	protected function addCSS($id, $path, $deps = [], $version = false, $media = 'all')
	{
		if ( !Stringify::contains($path, 'http') ) {
		    $path = $this->getPluginUrl($path);
		}
		wp_register_style($id,$path,$deps,$version,$media);
		wp_enqueue_style($id);
	}

	/**
	 * Register and Enqueue a new script
	 * @see /reference/functions/wp_register_script/
	 * @see /reference/functions/wp_enqueue_script/
	 *
	 * @access protected
	 * @param string $id
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $footer false
	 * @return void
	 */
	protected function addJS($id, $path, $deps = [], $version = false, $footer = false)
	{
		if ( !Stringify::contains($path, 'http') ) {
		    $path = $this->getPluginUrl($path);
		}
		wp_register_script($id,$path,$deps,$version,$footer);
		wp_enqueue_script($id);
	}

	/**
	 * Determines whether a script has been added to the queue
	 * @see /reference/functions/wp_register_script/
	 * @see /reference/functions/wp_enqueue_script/
	 *
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
	 * @see /reference/functions/wp_dequeue_style/
	 * @see /reference/functions/wp_deregister_style/
	 *
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
	 * @see /reference/functions/wp_dequeue_script/
	 * @see /reference/functions/wp_deregister_script/
	 *
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
	 * @see /reference/functions/wp_deregister_script/
	 *
	 * @access protected
	 * @param string $id
	 * @param object $object
	 * @param array $content
	 * @return boolean
	 */
	protected function localizeJS($id, $object, $content = [])
	{
		wp_localize_script($id,$object,$content);
	}

	/**
	 * Register a settings and its data
	 * @see /reference/functions/register_setting/
	 *
	 * @access protected
	 * @param string $group
	 * @param string $option
	 * @param array $args
	 * @return void
	 */
	protected function registerOption($group, $option, $args = [])
	{
		register_setting($group,$option,$args);
	}

	/**
	 * Register a settings and its data
	 * @see /reference/functions/register_setting/
	 *
	 * @access protected
	 * @param string $option
	 * @param mixed $value
	 * @return inherit
	 */
	protected function addOption($option, $value)
	{
		return add_option($option, Stringify::serialize($value));
	}

	/**
	 * Retrieves an option value based on an option name
	 * @see /reference/functions/get_option/
	 *
	 * @access protected
	 * @param string $option
	 * @param string $default null
	 * @return mixed
	 */
	protected function getOption($option, $default = null)
	{
		$option = Stringify::unserialize(get_option($option,$default));
		return Stringify::slashStrip($option);
	}

	/**
	 * Update the value of an option that was already added
	 * @see /reference/functions/update_option/
	 *
	 * @access protected
	 * @param string $option
	 * @param mixed $value
	 * @return inherit
	 */
	protected function updateOption($option, $value)
	{
		return update_option($option,$value);
	}

	/**
	 * Removes option by name
	 * @see /reference/functions/delete_option/
	 *
	 * @access protected
	 * @param string $option
	 * @return inherit
	 */
	protected function removeOption($option)
	{
		return delete_option($option);
	}

	/**
	 * Add a top-level menu page
	 * @see /reference/functions/add_menu_page/
	 *
	 * @access protected
	 * @param string $title
	 * @param string $menu
	 * @param string $capability
	 * @param string $slug
	 * @param callable $callable
	 * @param string $icon
	 * @param boolean $customIcon false
	 * @param int $position 20
	 * @return inherit
	 */
	protected function addMenuPage($title, $menu, $capability, $slug, $callable, $icon = 'admin-plugins', $customIcon = false, $position = 20)
	{
		if ($customIcon) {
			$icon = "dashicons-{$icon}";
		}
		return add_menu_page($title,$menu,$capability,$slug,$callable,$icon,$position);
	}

	/**
	 * Add a top-level menu page
	 * @see /reference/functions/add_submenu_page/
	 *
	 * @access protected
	 * @param string $parent
	 * @param string $title
	 * @param string $menu
	 * @param string $capability
	 * @param string $slug
	 * @param callable $callable
	 * @return inherit
	 */
	protected function addSubMenuPage($parent, $title, $menu, $capability, $slug, $callable)
	{
		return add_submenu_page($parent,$title,$menu,$capability,$slug,$callable);
	}

	/**
	 * Add a top-level menu page
	 * @see /reference/functions/add_options_page/
	 *
	 * @access protected
	 * @param string $title
	 * @param string $menu
	 * @param string $capability
	 * @param string $slug
	 * @param callable $callable
	 * @return inherit
	 */
	protected function addOptionPage($title, $menu, $capability, $slug, $method)
	{
		return add_options_page($title,$menu,$capability,$slug,$method);
	}

	/**
	 * Add Metabox
	 * @see /reference/functions/add_meta_box/
	 *
	 * @access protected
	 * @param string $id
	 * @param string $title
	 * @param callback $callback
	 * @param mixed $screen
	 * @param string $context
	 * @param string $priority
	 * @param array $args null
	 * @return void
	 *
	 * action : add_meta_boxes
	 * action : add_meta_boxes_{type}
	 */
	protected function addMetabox($id, $title, $callback, $screen, $context = 'advanced', $priority = 'high', $args = null)
	{
		add_meta_box($id,$title,$callback,$screen,$context,$priority,$args);
	}

	/**
	 * Retrieves a URL within the plugins or mu-plugins directory
	 *
	 * @access public
	 * @param string $path
	 * @param string $plugin
	 * @return string
	 */
	protected function getPluginUrl($path = '', $plugin = '')
	{
		return plugins_url($path,$plugin);
	}

	/**
	 * Retrieves a URL within the plugins or mu-plugins directory
	 *
	 * @access public
	 * @param string $plugin
	 * @return string
	 */
	protected function getPluginDir($plugin = null)
	{
		return isset($plugin) 
		? Stringify::formatPath( WP_PLUGIN_DIR . $plugin ) : WP_PLUGIN_DIR;
	}

	/**
	 * Retrieves current theme url
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	protected function getThemeUrl()
	{
		return get_stylesheet_directory_uri();
	}

	/**
	 * Retrieves current theme directory
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	protected function getThemeDir()
	{
		return Stringify::formatPath( get_stylesheet_directory() );
	}

	/**
	 * Check is WordPress Admin
	 *
	 * @access public
	 * @param void
	 * @return boolean
	 */
	protected function isAdmin()
	{
		if ( is_admin() ) {
			return true;
		}
		return false;
	}

	/**
	 * Check user logged in
	 * @see /reference/functions/is_user_logged_in/
	 *
	 * @access protected
	 * @param void
	 * @return inherit
	 */
	protected function isLoggedIn()
	{
		return is_user_logged_in();
	}

	/**
	 * Check user exists
	 * @see /reference/functions/username_exists/
	 * @see /reference/functions/email_exists/
	 *
	 * @access protected
	 * @param mixed $user
	 * @param string $property
	 * @return inherit
	 */
	protected function isUser($user, $property = 'username')
	{
		switch ( Stringify::lowercase($property) ) {
			case 'username':
				return username_exists(sanitize_user($user));
				break;
			case 'email':
				return email_exists($user);
				break;
			case 'id':
				$id = intval($user);
				$user = new \WP_User($id);
				return $user->exists();
				break;
		}
	}

	/**
	 * Get user permission
	 * @see /reference/functions/get_current_user_id/
	 * @see /reference/functions/user_can/
	 *
	 * @access protected
	 * @param mixed $id null
	 * @param string $capability
	 * @param mixed $args
	 * @return inherit
	 */
	protected function hasPermission($id = null, $capability = 'edit_posts', $args = [])
	{
		$id = ($id) ? intval($id) : get_current_user_id();
		return user_can($id,$capability,$args);
	}

	/**
	 * Get role
	 * @see /reference/functions/get_current_user_id/
	 *
	 * @access protected
	 * @param mixed $id null
	 * @return array
	 */
	protected function getRole($id = null)
	{
		$id = ($id) ? intval($id) : get_current_user_id();
		$user = new \WP_User($id);
		return (array)$user->roles;
	}

	/**
	 * Add role
	 * @see /reference/functions/add_role/
	 *
	 * @access protected
	 * @param string $display
	 * @param string $role null
	 * @param array $capability null
	 * @return inherit
	 */
	protected function addRole($display, $role = null, $capability = [])
	{
		$role = ($role) ? $role : Stringify::slugify($display);
		$role = Stringify::replace('-','_',$role);
		return add_role($role,$display,$capability);
	}

	/**
	 * Remove role
	 * @see /reference/functions/remove_role/
	 *
	 * @access protected
	 * @param string $role
	 * @return void
	 */
	protected static function removeRole($role)
	{
		remove_role($role);
	}

	/**
	 * Add capability
	 *
	 * @access protected
	 * @param string $role
	 * @param string $capability
	 * @param boolean $grant true
	 * @return void
	 */
	protected function addCapability($role, $capability, $grant = true)
	{
		$role = get_role($role);
		$role->add_cap($capability,$grant);
	}

	/**
	 * Remove capability
	 *
	 * @access protected
	 * @param string $role
	 * @param string $capability
	 * @return void
	 */
	protected static function removeCapability($role, $capability)
	{
		$role = get_role($role);
		$role->remove_cap($capability);
	}

	/**
	 * Redirects to another page
	 * @see /reference/functions/wp_redirect/
	 *
	 * @access public
	 * @param string $location
	 * @param int $status 301
	 * @return void
	 */
	public function redirect($location, $status = 301)
	{
		wp_redirect($location,$status);
		exit();
	}

	/**
	 * Kill WordPress execution and display HTML message with error message
	 *
	 * @access protected
	 * @param string $message
	 * @param string $title
	 * @param array $args
	 * @return void
	 */
	protected function except($message = '', $title = '', $args = [])
	{
		wp_die($message,$title,$args);
	}
}
