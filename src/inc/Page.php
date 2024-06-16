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

final class Page
{
	/**
	 * Add options page.
	 *
	 * @access public
	 * @param string $title
	 * @param string $menu
	 * @param string $cap
	 * @param string $slug
	 * @param callable $cb callback
	 * @return mixed
	 */
	public static function add(string $title, string $menu, string $cap, string $slug, $cb)
	{
		return add_options_page($title, $menu, $cap, $slug, $cb);
	}

	/**
	 * Add menu page.
	 *
	 * @access public
	 * @param string $t Title
	 * @param string $m Menu
	 * @param string $c Capability
	 * @param string $s Slug
	 * @param callable $cb Callback
	 * @param string $i Icon
	 * @param int $p position
	 * @return string
	 */
	public static function addMenu(string $t, string $m, string $c, string $s, $cb, string $i = 'none', int $p = 20) : string
	{
		return add_menu_page($t, $m, $c, $s, $cb, $i, $p);
	}

	/**
	 * Add submenu page.
	 *
	 * @access public
	 * @param string $p Parent
	 * @param string $t Title
	 * @param string $m Menu
	 * @param string $c Capability
	 * @param string $s Slug
	 * @param callable $cb Callback
	 * @return mixed
	 */
	public static function addSubMenu(string $p, string $t, string $m, string $c, string $s, $cb)
	{
		return add_submenu_page($p, $t, $m, $c, $s, $cb);
	}

	/**
	 * Reset submenu first item.
	 *
	 * @access public
	 * @param string $parent
	 * @param string $title
	 * @param string $icon
	 * @return void
	 */
	public static function resetSubMenu(string $parent, ?string $title = null, ?string $icon = null)
	{
		global $submenu;
		if ( isset($submenu[$parent]) ) {
			if ( $title ) {
				if ( $icon ) {
					$title = "{$icon} {$title}";
				}
				$submenu[$parent][0][0] = $title;

			} else {
				unset($submenu[$parent][0]);
			}
		}
	}
	
	/**
	 * Add menu bar.
	 *
	 * @access public
	 * @param object $bar
	 * @param array $settings
	 * @return void
	 */
	public static function addMenuBar(object $bar, array $settings = [])
	{
		$bar->add_menu($settings);
	}

	/**
	 * Add Metabox,
	 * [Action: add_meta_boxes],
	 * [Action: add_meta_boxes_{type}].
	 * 
	 * @access public
	 * @param string $id
	 * @param string $t Title
	 * @param callable $cb Callback
	 * @param mixed $s Screen
	 * @param string $c Context
	 * @param string $p Priority
	 * @param array $args
	 * @return void
	 */
	public static function addMetabox(string $id, string $t, $cb, $s, string $c = 'advanced', string $p = 'high', ?array $args = null)
	{
		add_meta_box($id, $t, $cb, $s, $c, $p, $args);
	}

	/**
	 * Check whether page is admin.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isAdmin() : bool
	{
		return is_admin();
	}

	/**
	 * Check whether page is login.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isLogin() : bool
	{
		global $pagenow;
		return ($pagenow == 'wp-login.php');
	}

	/**
	 * Get current screen.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function screen()
	{
		return get_current_screen();
	}

	/**
	 * Check is current screen.
	 *
	 * @access public
	 * @param string $screen
	 * @return bool
	 */
	public static function isScreen(string $screen) : bool
	{
		if ( !self::screen() ) {
			return false;
		}
		$screen = "toplevel_page_{$screen}";
		return (self::screen()->base == $screen);
	}

	/**
	 * Add help tab.
	 *
	 * @access public
	 * @param array $settings
	 * @return void
	 */
	public static function addHelpTab(array $settings)
	{
		if ( !self::screen() ) return;
		self::screen()->add_help_tab($settings);
	}

	/**
	 * Add help sidebar.
	 *
	 * @access public
	 * @param string $html
	 * @return void
	 */
	public static function addHelpSidebar(string $html)
	{
		if ( !self::screen() ) return;
		self::screen()->set_help_sidebar($html);
	}

	/**
	 * Render settings fields by group.
	 *
	 * @access public
	 * @param string $group
	 * @return void
	 */
	public static function doSettingsFields(string $group)
	{
		settings_fields($group);
	}

	/**
	 * Render settings sections by page.
	 *
	 * @access public
	 * @param string $page
	 * @return void
	 */
	public static function doSettingsSections(string $page)
	{
		do_settings_sections($page);
	}

	/**
	 * Render settings submit button.
	 *
	 * @access public
	 * @param string $text
	 * @param string $type
	 * @param string $name
	 * @param bool $wrap
	 * @param mixed $args
	 * @return void
	 */
	public static function doSettingsSubmit(?string $text = null, string $type = 'primary', string $name = 'submit', bool $wrap = true, $args = null)
	{
		submit_button($text, $type, $name, $wrap, $args);
	}

	/**
	 * Add dashboard widget.
	 *
	 * @access public
	 * @param string $id
	 * @param string $name
	 * @param callable $cb callback
	 * @param callable $ctrl control
	 * @param array $args
	 * @param string $c context
	 * @param string $p priority
	 * @return void
	 */
	public static function addDashboardWidget(string $id, string $name, $cb, $ctrl = null, ?array $args = null, string $c = 'normal', string $p = 'core')
	{
		wp_add_dashboard_widget($id, $name, $cb, $ctrl, $args, $c, $p);
	}
}
