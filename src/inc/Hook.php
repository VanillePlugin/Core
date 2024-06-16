<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

final class Hook
{
	/**
	 * Register plugin activation hook.
	 * [Action: activate_{pluginMain}.php].
	 *
	 * @access public
	 * @param string $file
	 * @param callable $callback
	 * @return void
	 */
	public static function activation(string $file, $callback)
	{
		register_activation_hook($file, $callback);
	}

	/**
	 * Register plugin deactivation hook.
	 * [Action: deactivate_{pluginMain}.php].
	 *
	 * @access public
	 * @param string $file
	 * @param callable $callback
	 * @return void
	 */
	public static function deactivation(string $file, $callback)
	{
		register_deactivation_hook($file, $callback);
	}

	/**
	 * Register plugin uninstall hook.
	 * Use class name instead of object ['Plugin', 'uninstall'].
	 * @uses isAdmin()
	 *
	 * @access public
	 * @param string $file
	 * @param callable $callback
	 * @return void
	 */
	public static function uninstall(string $file, $callback)
	{
		if ( Page::isAdmin() ) {
			register_uninstall_hook($file, $callback);
		}
	}

	/**
	 * Register plugin action links hook.
	 *
	 * @access public
	 * @param string $file
	 * @param callable $callback
	 * @return void
	 */
	public static function action(string $file, $callback)
	{
		self::addFilter("plugin_action_links_{$file}", $callback);
		self::addFilter("network_admin_plugin_action_links_{$file}", $callback);
	}
	
	/**
	 * Add hook action.
	 *
	 * @access public
	 * @param string $hook
	 * @param callable $callback
	 * @param int $priority
	 * @param int $args
	 * @return void
	 */
	public static function addAction(string $hook, $callback, int $priority = 10, int $args = 1)
	{
		add_action(self::format($hook), $callback, $priority, $args);
	}

	/**
	 * Remove hook action.
	 *
	 * @access public
	 * @param string $hook
	 * @param callable $callback
	 * @param int $priority
	 * @return bool
	 */
	public static function removeAction(string $hook, $callback, int $priority = 10) : bool
	{
		return remove_action(self::format($hook), $callback, $priority);
	}

	/**
	 * Do hook action.
	 *
	 * @access public
	 * @param string $hook
	 * @param mixed $args
	 * @return void
	 */
	public static function doAction(string $hook, $args = null)
	{
		do_action($hook, $args);
	}

	/**
	 * Check hook action.
	 *
	 * @access public
	 * @param string $hook
	 * @param mixed $callback
	 * @return mixed
	 */
	public static function hasAction(string $hook, $callback = false)
	{
		return has_action($hook, $callback);
	}

	/**
	 * Add hook filter.
	 *
	 * @access public
	 * @param string $hook
	 * @param callable $callback
	 * @param int $priority
	 * @param int $args
	 * @return void
	 */
	public static function addFilter(string $hook, $callback, int $priority = 10, int $args = 1)
	{
		add_filter(self::format($hook), $callback, $priority, $args);
	}

	/**
	 * Remove hook filter.
	 *
	 * @access public
	 * @param string $hook
	 * @param callable $callback
	 * @param int $priority
	 * @return bool
	 */
	public static function removeFilter(string $hook, $callback, int $priority = 10) : bool
	{
		return remove_filter($hook, $callback, $priority);
	}

	/**
	 * Apply hook filter.
	 *
	 * @access public
	 * @param string $hook
	 * @param mixed $value
	 * @param mixed $args
	 * @return mixed
	 */
	public static function applyFilter(string $hook, $value, $args = null)
	{
		return apply_filters($hook, $value, $args);
	}

	/**
	 * Check hook filter.
	 *
	 * @access public
	 * @param string $hook
	 * @param mixed $callback
	 * @return mixed
	 */
	public static function hasFilter(string $hook, $callback = false)
	{
		return has_filter($hook, $callback);
	}

	/**
	 * Add CSS.
	 *
	 * @access public
	 * @param string $id
	 * @param string $path
	 * @param array $deps
	 * @param mixed $version
	 * @param string $media
	 * @return void
	 */
	public static function addCSS(string $id, string $path, array $deps = [], $version = false, string $media = 'all')
	{
		if ( !Stringify::contains($path, 'http') ) {
		    $path = Plugin::getUrl($path);
		}
		wp_register_style($id, $path, $deps, $version, $media);
		wp_enqueue_style($id);
	}

	/**
	 * Add JS.
	 *
	 * @access public
	 * @param string $id
	 * @param string $path
	 * @param array $deps
	 * @param mixed $version
	 * @param bool $footer
	 * @return void
	 */
	public static function addJS(string $id, string $path, array $deps = [], $version = false, bool $footer = false)
	{
		if ( !Stringify::contains($path, 'http') ) {
		    $path = Plugin::getUrl($path);
		}
		wp_register_script($id, $path, $deps, $version, $footer);
		wp_enqueue_script($id);
	}
	
	/**
	 * Check enqueued CSS.
	 *
	 * @access public
	 * @param string $id
	 * @param string $list
	 * @return bool
	 */
	public static function isCSS(string $id, string $list = 'enqueued') : bool
	{
		return wp_style_is($id, $list);
	}

	/**
	 * Check enqueued JS.
	 *
	 * @access public
	 * @param string $id
	 * @param string $list
	 * @return bool
	 */
	public static function isJS(string $id, string $list = 'enqueued') : bool
	{
		return wp_script_is($id, $list);
	}

	/**
	 * Remove enqueued CSS.
	 *
	 * @access public
	 * @param string $id 
	 * @return void
	 */
	public static function removeCSS(string $id)
	{
		wp_dequeue_style($id);
		wp_deregister_style($id);
	}

	/**
	 * Remove enqueued JS.
	 *
	 * @access public
	 * @param string $id 
	 * @return void
	 */
	public static function removeJS(string $id)
	{
		wp_dequeue_script($id);
		wp_deregister_script($id);
	}

	/**
	 * Assign enqueued JS data.
	 *
	 * @access public
	 * @param string $id
	 * @param string $object
	 * @param array $data
	 * @return bool
	 */
	public static function assignJS(string $id, string $object, array $data = []) : bool
	{
		return wp_localize_script($id, $object, $data);
	}

	/**
	 * Check whether script exists.
	 * 
	 * @access public
	 * @param string $search
	 * @param mixed $scripts
	 * @return bool
	 */
	public static function hasScript(string $search, $scripts) : bool
	{
	    if ( !TypeCheck::isArray($scripts) ) {
	    	$scripts = [$scripts];
	    }
	    foreach ($scripts as $script) {
	        if ( Stringify::contains($search, $script) ) {
	        	return true;
	        }
	    }
	    return false;
	}

	/**
	 * Remove excluded scripts.
	 *
	 * @access public
	 * @param array $exclude
	 * @return void
	 */
	public static function removeScripts(array $exclude)
	{
		foreach (GlobalConst::scripts()->queue as $script) {
			if ( self::hasScript($script, $exclude) !== false ) {
				self::removeJS($script);
			}
		}

		foreach (GlobalConst::styles()->queue as $style) {
			if ( self::hasScript($style, $exclude) !== false ) {
				self::removeCSS($style);
			}
		}
	}

	/**
	 * Format hook.
	 *
	 * @access private
	 * @param string $hook
	 * @return string
	 */
	private static function format(string $hook) : string
	{
		switch ( Stringify::lowercase($hook) ) {
			case 'loaded':
				$hook = 'wp_loaded';
				break;
			case 'head':
				$hook = 'wp_head';
				break;
			case 'body':
				$hook = 'wp_body_open';
				break;
			case 'content':
				$hook = 'the_content';
				break;
			case 'footer':
				$hook = 'wp_footer';
				break;
			case 'enqueue-scripts':
				$hook = 'wp_enqueue_scripts';
				break;
			case 'body-class':
				$hook = 'body_class';
				break;
			case 'user-register':
				$hook = 'user_register';
				break;
			case 'user-auth':
				$hook = 'wp_authenticate_user';
				break;
			case 'login-enqueue-scripts':
				$hook = 'login_enqueue_scripts';
				break;
			case 'login-body-class':
				$hook = 'login_body_class';
				break;
			case 'login-header-url':
				$hook = 'login_headerurl';
				break;
			case 'login-header-text':
				$hook = 'login_headertext';
				break;
			case 'login-form':
				$hook = 'login_form';
				break;
			case 'login-form-defaults':
				$hook = 'login_form_defaults';
				break;
			case 'amp-css':
				$hook = 'amp_post_template_css';
				break;
			case 'amp-head':
				$hook = 'amp_post_template_head';
				break;
			case 'amp-footer':
				$hook = 'amp_post_template_footer';
				break;
			case 'plugins-loaded':
				$hook = 'plugins_loaded';
				break;
			case 'plugin-row':
				$hook = 'plugin_row_meta';
				break;
			case 'admin-init':
				$hook = 'admin_init';
				break;
			case 'admin-menu':
				$hook = 'admin_menu';
				break;
			case 'admin-bar-menu':
				$hook = 'admin_bar_menu';
				break;
			case 'show-admin-bar':
				$hook = 'show_admin_bar';
				break;
			case 'admin-enqueue-scripts':
				$hook = 'admin_enqueue_scripts';
				break;
			case 'admin-body-class':
				$hook = 'admin_body_class';
				break;
			case 'admin-footer-text':
				$hook = 'admin_footer_text';
				break;
			case 'admin-notices':
				$hook = 'admin_notices';
				break;
			case 'mail':
				$hook = 'wp_mail';
				break;
			case 'mail-from':
				$hook = 'wp_mail_from';
				break;
			case 'mail-name':
				$hook = 'wp_mail_from_name';
				break;
			case 'update-footer':
				$hook = 'update_footer';
				break;
			case 'upgrader-process-complete':
				$hook = 'upgrader_process_complete';
				break;
			case 'save-post':
				$hook = 'save_post';
				break;
			case 'insert-post-data':
				$hook = 'wp_insert_post_data';
				break;
			case 'media-button':
				$hook = 'media_buttons';
				break;
			case 'auto-update-plugin':
				$hook = 'auto_update_plugin';
				break;
			case 'template-redirect':
				$hook = 'template_redirect';
				break;
			case 'dashboard-setup':
				$hook = 'wp_dashboard_setup';
				break;
			case 'rest-api':
				$hook = 'rest_api_init';
				break;
			case 'post-status':
				$hook = 'transition_post_status';
				break;
			case 'cron-schedules':
				$hook = 'cron_schedules';
				break;
		}
        return $hook;
	}
}
