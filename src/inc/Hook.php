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

/**
 * @see https://developer.wordpress.org/apis/hooks/
 */
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
	 * Use static class [static::class, 'uninstall'].
	 *
	 * @access public
	 * @param string $file
	 * @param callable $callback
	 * @return void
	 */
	public static function uninstall(string $file, $callback)
	{
		register_uninstall_hook($file, $callback);
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
		add_action(Format::hook($hook), $callback, $priority, $args);
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
		return remove_action(Format::hook($hook), $callback, $priority);
	}

	/**
	 * Do hook action.
	 *
	 * @access public
	 * @param string $hook
	 * @param mixed $args
	 * @return void
	 */
	public static function doAction(string $hook, ...$args)
	{
		do_action(Format::hook($hook), ...$args);
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
		return has_action(Format::hook($hook), $callback);
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
		add_filter(Format::hook($hook), $callback, $priority, $args);
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
		return remove_filter(Format::hook($hook), $callback, $priority);
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
	public static function applyFilter(string $hook, $value, ...$args)
	{
		return apply_filters(Format::hook($hook), $value, ...$args);
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
		return has_filter(Format::hook($hook), $callback);
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
		$scripts = Globals::scripts()->queue ?? [];
		foreach ($scripts as $script) {
			if ( self::hasScript($script, $exclude) !== false ) {
				self::removeJS($script);
			}
		}
		$styles = Globals::styles()->queue ?? [];
		foreach ($styles as $style) {
			if ( self::hasScript($style, $exclude) !== false ) {
				self::removeCSS($style);
			}
		}
	}
}
