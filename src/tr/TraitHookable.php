<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\{
    Hook, Shortcode, Globals
};

/**
 * Define hooking and shortcoding functions.
 */
trait TraitHookable
{
	/**
	 * Register plugin activation hook.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function registerActivation(string $file, $callback)
	{
		Hook::activation($file, $callback);
	}

	/**
	 * Register plugin uninstall hook.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function registerDeactivation(string $file, $callback)
	{
		Hook::deactivation($file, $callback);
	}

	/**
	 * Register plugin uninstall hook.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function registerUninstall(string $file, $callback)
	{
		Hook::uninstall($file, $callback);
	}

	/**
	 * Register plugin action links hook.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function registerAction(string $file, $callback)
	{
		Hook::action($file, $callback);
	}

	/**
	 * Add hook action.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addAction(string $name, $callback, int $priority = 10, int $args = 1)
	{
		Hook::addAction($name, $callback, $priority, $args);
	}

	/**
	 * Remove hook action.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeAction(string $name, $callback, int $priority = 10) : bool
	{
		return Hook::removeAction($name, $callback, $priority);
	}

	/**
	 * Remove all actions.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeActions(string $name, $priority = false)
	{
		return Hook::removeActions($name, $priority);
	}

	/**
	 * Do hook action.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function doAction(string $name, ...$args)
	{
		Hook::doAction($name,...$args);
	}

	/**
	 * Check hook action.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasAction(string $name, $callback = false)
	{
		return Hook::hasAction($name, $callback);
	}

	/**
	 * Add hook filter.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addFilter(string $name, $callback, int $priority = 10, int $args = 1)
	{
		Hook::addFilter($name, $callback, $priority, $args);
	}

	/**
	 * Remove hook filter.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeFilter(string $name, $callback, int $priority = 10) : bool
	{
		return Hook::removeFilter($name, $callback, $priority);
	}

	/**
	 * Remove all filters.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeFilters(string $name, $priority = false)
	{
		return Hook::removeFilters($name, $priority);
	}

	/**
	 * Apply hook filter.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function applyFilter(string $name, $value, ...$args)
	{
		return Hook::applyFilter($name, $value, ...$args);
	}

	/**
	 * Check hook filter.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasFilter(string $name, $callback = false)
	{
		return Hook::hasFilter($name, $callback);
	}

	/**
	 * Add CSS.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addCSS(string $id, string $path, array $deps = [], $version = false, string $media = 'all')
	{
		Hook::addCSS($id, $path, $deps, $version, $media);
	}

	/**
	 * Include existing CSS.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function includeCSS(string $id, string $path, array $deps = [], $version = false, string $media = 'all')
	{
		$this->addCSS($id, Globals::includesUrl($path), $deps, $version, $media);
	}

	/**
	 * Add JS.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addJS(string $id, string $path, array $deps = [], $version = false, bool $footer = false)
	{
		Hook::addJS($id, $path, $deps, $version, $footer);
	}

	/**
	 * Include existing JS.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function includeJS(string $id, string $path, array $deps = [], $version = false, bool $footer = false)
	{
		$this->addJS($id, Globals::includesUrl($path), $deps, $version, $footer);
	}

	/**
	 * Check enqueued CSS.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isCSS(string $id, string $list = 'enqueued') : bool
	{
		return Hook::isCSS($id, $list);
	}

	/**
	 * Check enqueued JS.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isJS(string $id, string $list = 'enqueued') : bool
	{
		return Hook::isJS($id, $list);
	}

	/**
	 * Remove CSS.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeCSS(string $id)
	{
		Hook::removeCSS($id);
	}

	/**
	 * Remove JS.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeJS(string $id)
	{
		Hook::removeJS($id);
	}

	/**
	 * Assign enqueued JS data.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function assignJS(string $id, string $object, array $data = []) : bool
	{
		return Hook::assignJS($id, $object, $data);
	}
	
	/**
	 * Check whether script exists.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasScript(string $search, $scripts) : bool
	{
	    return Hook::hasScript($search, $scripts);
	}

	/**
	 * Remove excluded scripts.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeScripts(array $exclude)
	{
		Hook::removeScripts($exclude);
	}

	/**
	 * Add shortcode.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addShortcode(string $tag, $callback)
	{
		Shortcode::add($tag, $callback);
	}

	/**
	 * Assign content to shortcode.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function assignShortcode(string $content, bool $ignore = false)
	{
		Shortcode::do($content, $ignore);
	}

	/**
	 * Render shortcode.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function renderShortcode(string $content, bool $ignore = false)
	{
		Shortcode::render($content, $ignore);
	}

	/**
	 * Remove shortcode.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeShortcode(string $tag)
	{
		Shortcode::remove($tag);
	}

	/**
	 * Check whether shortcode registered.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasShortcode(string $tag) : bool
	{
		return Shortcode::has($tag);
	}
}
