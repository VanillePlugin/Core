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

namespace VanillePlugin\tr;

use VanillePlugin\inc\Page;

trait TraitRenderable
{
	/**
	 * Add options page.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addOptionPage(string $title, string $menu, string $cap, string $slug, $cb)
	{
		return Page::add($title, $menu, $cap, $slug, $cb);
	}

	/**
	 * Add menu page.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addMenuPage(array $settings = []) : string
	{
		extract($settings);
		if ( empty($icon) ) {
			$icon = 'dashicons-admin-plugins';
		}
		return Page::addMenu($title, $menu, $cap, $slug, $callback, $icon, $position);
	}

	/**
	 * Add submenu page.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addSubMenuPage(array $settings = [])
	{
		extract($settings);
		if ( $icon ) {
			$menu = "{$icon} {$menu}";
		}
		return Page::addSubMenu($parent, $title, $menu, $cap, $slug, $callback);
	}

	/**
	 * Reset submenu first item.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function resetSubMenuPage(string $parent, ?string $title = null, ?string $icon = null)
	{
		Page::resetSubMenu($parent, $title, $icon);
	}

	/**
	 * Add menu bar.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addMenuBar(object $bar, array $settings = [])
	{
		Page::addMenuBar($bar, $settings);
	}

	/**
	 * Add metabox.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addMetabox(string $id, string $t, $cb, $s, string $c = 'advanced', string $p = 'high', ?array $args = null)
	{
		Page::addMetabox($id, $t, $cb, $s, $c, $p, $args);
	}

	/**
	 * Check whether page is admin.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isAdmin() : bool
	{
		return Page::isAdmin();
	}

	/**
	 * Check whether page is login.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isLogin() : bool
	{
		return Page::isLogin();
	}

	/**
	 * Get current screen.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getScreen()
	{
		return Page::screen();
	}

	/**
	 * Check is current screen.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isScreen(string $screen) : bool
	{
		return Page::isScreen($screen);
	}

	/**
	 * Add help menu (tab).
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addHelpMenu(array $settings)
	{
		Page::addHelpMenu($settings);
	}

	/**
	 * Add help sidebar.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addHelpSidebar(string $html)
	{
		Page::addHelpSidebar($html);
	}

	/**
	 * Render settings fields by group.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function doSettingsFields(string $group)
	{
		Page::doSettingsFields($group);
	}

	/**
	 * Render settings sections by page.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function doSettingsSections(string $page)
	{
		Page::doSettingsSections($page);
	}

	/**
	 * Render settings submit button.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function doSettingsSubmit(?string $text = null)
	{
		Page::doSettingsSubmit($text);
	}

	/**
	 * Render settings submit button.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addDashboardWidget(string $id, string $name, $cb, $ctrl = null, ?array $args = null, string $c = 'normal', string $p = 'core')
	{
		Page::addDashboardWidget($id, $name, $cb, $ctrl, $args, $c, $p);
	}

	/**
	 * Get checkbox attribute.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getCheckbox($data, $value = true) : string
	{
		return Page::getCheckbox($data, $value);
	}
}
