<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.2
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\GlobalConst;
use VanillePlugin\inc\Stringify;
use \WP_User;

/**
 * Wrapper class for advanced WordPress global functions,
 * Defines only base functions used by plugins (PluginNameSpaceInterface).
 * 
 * @see https://developer.wordpress.org/
 */
class WordPress
{
	/**
	 * Set the activation hook for a plugin.
	 *
	 * @access protected
	 * @param string $file
	 * @param callable $callback
	 * @return void
	 */
	protected function registerActivation($file, $callback)
	{
		register_activation_hook($file,$callback);
	}

	/**
	 * Set the deactivation hook for a plugin.
	 *
	 * @access protected
	 * @param string $file
	 * @param callable $callback
	 * @return void
	 */
	protected function registerDeactivation($file, $callback)
	{
		register_deactivation_hook($file,$callback);
	}

	/**
	 * Set the uninstallation hook for a plugin,
	 * Use class name instead of object ['Plugin','uninstall'].
	 *
	 * @access protected
	 * @param string $file
	 * @param callable $callback
	 * @return void
	 * @see Used isAdmin() for better performance
	 */
	protected function registerUninstall($file, $callback)
	{
		if ( $this->isAdmin() ) {
			register_uninstall_hook($file,$callback);
		}
	}
	
	/**
	 * Add a shortcode.
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
	 * Remove a shortcode.
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
	 * Checks whether a shortcode exists.
	 *
	 * @access protected
	 * @param string $tag
	 * @return bool
	 */
	protected function shortcodeExists($tag)
	{
		return shortcode_exists($tag);
	}

	/**
	 * Hook a callback on a specific action hook.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $callback
	 * @param int $priority
	 * @param int $args
	 * @return true
	 */
	protected function addAction($hook, $callback, $priority = 10, $args = 1)
	{
		switch ( Stringify::lowercase($hook) ) {
			case 'head':
				$hook = 'wp_head';
				break;
			case 'body':
				$hook = 'wp_body_open';
				break;
			case 'footer':
				$hook = 'wp_footer';
				break;
			case 'content':
				$hook = 'the_content';
				break;
		}
		return add_action($hook,$callback,$priority,$args);
	}

	/**
	 * Remove a callback from a specified action hook.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $callback
	 * @param int $priority
	 * @return bool
	 */
	protected function removeAction($hook, $callback, $priority = 10)
	{
		switch ( Stringify::lowercase($hook) ) {
			case 'head':
				$hook = 'wp_head';
				break;
			case 'body':
				$hook = 'wp_body_open';
				break;
			case 'footer':
				$hook = 'wp_footer';
				break;
			case 'content':
				$hook = 'the_content';
				break;
		}
		return remove_action($hook,$callback,$priority);
	}

	/**
	 * Call the callback from a specified action hook.
	 *
	 * @access protected
	 * @param string $hook
	 * @param mixed $args
	 * @return void
	 */
	protected function doAction($hook, $args = null)
	{
		do_action($hook,$args);
	}

	/**
	 * Check whether any action has been registered for a hook.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $callback
	 * @return mixed
	 */
	protected function hasAction($hook, $callback = false)
	{
		return has_action($hook,$callback);
	}

	/**
	 * Hook a callback to a specific filter action.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $callback
	 * @param int $priority
	 * @param int $args
	 * @return true
	 */
	protected function addFilter($hook, $callback, $priority = 10, $args = 1)
	{
		switch ( Stringify::lowercase($hook) ) {
			case 'head':
				$hook = 'wp_head';
				break;
			case 'body':
				$hook = 'wp_body_open';
				break;
			case 'footer':
				$hook = 'wp_footer';
				break;
			case 'content':
				$hook = 'the_content';
				break;
		}
		return add_filter($hook,$callback,$priority,$args);
	}

	/**
	 * Remove a callback from a specified filter hook.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $callback
	 * @param int $priority
	 * @return bool
	 */
	protected function removeFilter($hook, $callback, $priority = 10)
	{
		return remove_filter($hook,$callback,$priority);
	}

	/**
	 * Calls the callback from a specified filter hook.
	 *
	 * @access protected
	 * @param string $hook
	 * @param mixed $value
	 * @param mixed $args
	 * @return mixed
	 */
	protected function applyFilter($hook, $value, $args = null)
	{
		return apply_filters($hook,$value,$args);
	}

	/**
	 * Check if any filter has been registered for a hook.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $callback
	 * @return bool
	 */
	protected function hasFilter($hook, $callback = false)
	{
		return has_filter($hook,$callback);
	}

	/**
	 * Register and Enqueue a CSS stylesheet.
	 *
	 * @access protected
	 * @param string $id
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version
	 * @param string $media
	 * @return void
	 */
	protected function addCSS($id, $path, $deps = [], $version = false, $media = 'all')
	{
		if ( !Stringify::contains($path,'http') ) {
		    $path = $this->getPluginUrl($path);
		}
		wp_register_style($id,$path,$deps,$version,$media);
		wp_enqueue_style($id);
	}

	/**
	 * Register and Enqueue a new script.
	 *
	 * @access protected
	 * @param string $id
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version
	 * @param string $footer
	 * @return void
	 */
	protected function addJS($id, $path, $deps = [], $version = false, $footer = false)
	{
		if ( !Stringify::contains($path,'http') ) {
		    $path = $this->getPluginUrl($path);
		}
		wp_register_script($id,$path,$deps,$version,$footer);
		wp_enqueue_script($id);
	}

	/**
	 * Determines whether a script has been added to the queue.
	 *
	 * @access protected
	 * @param string $id
	 * @param string $list
	 * @return bool
	 */
	protected function isJS($id, $list = 'enqueued')
	{
		if ( wp_script_is($id,$list)) {
			return true;
		}
		return false;
	}

	/**
	 * Remove a previously enqueued and registered CSS stylesheet.
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
	 * Remove a previously enqueued and registered script.
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
	 * Localize a script,
	 * Works on already added script only.
	 *
	 * @access protected
	 * @param string $id
	 * @param object $object
	 * @param array $content
	 * @return bool
	 */
	protected function localizeJS($id, $object, $content = [])
	{
		wp_localize_script($id,$object,$content);
	}

	/**
	 * Register a settings and its data.
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
	 * Register a settings and its data.
	 *
	 * @access protected
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	protected function addOption($option, $value)
	{
		return add_option($option,$value);
	}

	/**
	 * Retrieves an option value based on an option name.
	 *
	 * @access protected
	 * @param string $option
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getOption($option, $default = false)
	{
		return Stringify::deepStripSlash(
			get_option($option,$default)
		);
	}

	/**
	 * Update the value of an option that was already added.
	 *
	 * @access protected
	 * @param string $option
	 * @param mixed $value
	 * @return bool
	 */
	protected function updateOption($option, $value)
	{
		return update_option($option,$value);
	}

	/**
	 * Removes option by name.
	 *
	 * @access protected
	 * @param string $option
	 * @return bool
	 */
	protected function removeOption($option)
	{
		return delete_option($option);
	}

	/**
	 * Add a top-level menu page.
	 *
	 * @access protected
	 * @param string $title
	 * @param string $menu
	 * @param string $cap capability
	 * @param string $slug
	 * @param callable $cb callback
	 * @param string $icon
	 * @param bool $i custom icon
	 * @param int $p position
	 * @return string
	 */
	protected function addMenuPage($title, $menu, $cap, $slug, $cb, $icon = 'admin-plugins', $i = false, $p = 20)
	{
		if ( $i ) {
			$icon = "dashicons-{$icon}";
		}
		return add_menu_page($title,$menu,$cap,$slug,$cb,$icon,$p);
	}

	/**
	 * Add a top-level menu page.
	 *
	 * @access protected
	 * @param string $parent
	 * @param string $title
	 * @param string $menu
	 * @param string $cap
	 * @param string $slug
	 * @param callable $cb callback
	 * @return mixed
	 */
	protected function addSubMenuPage($parent, $title, $menu, $cap, $slug, $cb)
	{
		return add_submenu_page($parent,$title,$menu,$cap,$slug,$cb);
	}

	/**
	 * Add a top-level menu page.
	 *
	 * @access protected
	 * @param string $title
	 * @param string $menu
	 * @param string $cap
	 * @param string $slug
	 * @param callable $cb callback
	 * @return mixed
	 */
	protected function addOptionPage($title, $menu, $cap, $slug, $cb)
	{
		return add_options_page($title,$menu,$cap,$slug,$cb);
	}

	/**
	 * Add Metabox.
	 *
	 * @access protected
	 * @param string $id
	 * @param string $title
	 * @param callable $cb callback
	 * @param mixed $screen
	 * @param string $context
	 * @param string $p priority
	 * @param array $args
	 * @return void
	 *
	 * Action: add_meta_boxes
	 * Action: add_meta_boxes_{type}
	 */
	protected function addMetabox($id, $title, $cb, $screen, $context = 'advanced', $p = 'high', $args = null)
	{
		add_meta_box($id,$title,$cb,$screen,$context,$p,$args);
	}

	/**
	 * Retrieves a URL within the plugins or mu-plugins directory.
	 *
	 * @access protected
	 * @param string $path
	 * @param string $plugin
	 * @return string
	 */
	protected function getPluginUrl($path = '', $plugin = '')
	{
		return plugins_url($path,$plugin);
	}

	/**
	 * Retrieves plugin directory.
	 *
	 * @access protected
	 * @param string $plugin
	 * @return string
	 */
	protected function getPluginDir($plugin = null)
	{
		return GlobalConst::pluginDir($plugin);
	}

	/**
	 * Retrieves current theme URL.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getThemeUrl()
	{
		return get_stylesheet_directory_uri();
	}

	/**
	 * Retrieves current theme directory.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getThemeDir()
	{
		return Stringify::formatPath(get_stylesheet_directory());
	}

	/**
	 * Check whether current page is admin.
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function isAdmin()
	{
		return is_admin();
	}

	/**
	 * Get current user Id.
	 *
	 * @access protected
	 * @param void
	 * @return int
	 */
	protected function getCurrentUserId()
	{
		return get_current_user_id();
	}

	/**
	 * Get current user.
	 *
	 * @access protected
	 * @param void
	 * @return object
	 */
	protected function getCurrentUser()
	{
		return wp_get_current_user();
	}

	/**
	 * Check whether user is logged-in.
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function isLoggedIn()
	{
		return is_user_logged_in();
	}

	/**
	 * Check whether user exists.
	 *
	 * @access protected
	 * @param mixed $user
	 * @param string $property
	 * @return bool
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
				$id = (int)$user;
				$user = new WP_User($id);
				return $user->exists();
				break;
		}
	}

	/**
	 * Get user role(s).
	 *
	 * @access protected
	 * @param mixed $id, User Id
	 * @return array
	 */
	protected function getRole($id = null)
	{
		$id = ($id) ? (int)$id : $this->getCurrentUserId();
		$user = new WP_User($id);
		return (array)$user->roles;
	}

	/**
	 * Add role(s) to user.
	 *
	 * @access protected
	 * @param string $display
	 * @param string $role
	 * @param array $cap
	 * @return mixed
	 */
	protected function addRole($display, $role = null, $cap = [])
	{
		$role = ($role) ? (string)$role : Stringify::slugify($display);
		$role = Stringify::replace('-','_',$role);
		return add_role($role,$display,$cap);
	}

	/**
	 * Remove role.
	 *
	 * @access protected
	 * @param string $role
	 * @return void
	 */
	protected function removeRole($role)
	{
		remove_role($role);
	}

	/**
	 * Add user capability.
	 *
	 * @access protected
	 * @param string $role
	 * @param string $cap
	 * @param bool $grant
	 * @return void
	 */
	protected function addCapability($role, $cap, $grant = true)
	{
		$user = get_role($role);
		$user->add_cap($cap,$grant);
	}

	/**
	 * Remove user capability.
	 *
	 * @access protected
	 * @param string $role
	 * @param string $cap
	 * @return void
	 */
	protected function removeCapability($role, $cap)
	{
		$user = get_role($role);
		$user->remove_cap($cap);
	}

	/**
	 * Check whether current user has capability.
	 *
	 * @access public
	 * @param string $cap
	 * @param mixed $args
	 * @return bool
	 */
	public function hasCapability($cap = 'edit_posts', $args = null)
	{
		return current_user_can($cap,$args);
	}

	/**
	 * Check user permission (Capability).
	 *
	 * @access protected
	 * @param string $cap
	 * @param int $user
	 * @param mixed $args
	 * @return bool
	 */
	protected function hasPermission($cap = 'edit_posts', $user = null, $args = null)
	{
		$user = ($user) ? (int)$user : $this->getCurrentUserId();
		return user_can($user,$cap,$args);
	}

	/**
	 * Redirects to another page.
	 *
	 * @access protected
	 * @param string $location
	 * @param int $status
	 * @return void
	 */
	protected function redirect($location, $status = 301)
	{
		wp_redirect($location,$status);
		exit();
	}

	/**
	 * Authentication.
	 *
	 * @access protected
	 * @param string $user
	 * @param string $password
	 * @return mixed
	 */
	protected function authenticate($user, $password)
	{
		return wp_authenticate($user,$password);
	}

	/**
	 * Deactivate plugins.
	 *
	 * @access protected
	 * @param array $plugins
	 * @param bool $silent
	 * @return void
	 */
	protected function deactivatePlugins($plugins = [], $silent = true)
	{
		deactivate_plugins($plugins,$silent);
	}

	/**
	 * Check whether multisite is enabled.
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function isMultisite()
	{
		return is_multisite();
	}

	/**
	 * Check mobile.
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function isMobile()
	{
		return wp_is_mobile();
	}
}
