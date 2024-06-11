<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface AdminInterface
{
    /**
     * Setup plugin admin.
     * [Action: plugins-loaded].
     *
     * @param MenuInterface $menu
     * @param SettingsInterface $settings
     * @uses isAdmin()
     */
    function __construct(?MenuInterface $menu = null, ?SettingsInterface $settings = null);

    /**
	 * Init plugin admin.
     * [Action: plugins-loaded].
	 * 
	 * @return void
	 * @uses isPluginAdmin()
	 */
	function init();

	/**
	 * Add admin CSS.
	 * [Action: admin-enqueue-scripts].
	 *
	 * @return void
	 */
    function initCSS();

	/**
	 * Add admin JS.
	 * [Action: admin-enqueue-scripts].
	 * 
     * @return void
     */
    function initJS();

    /**
     * Add global admin CSS.
     * [Action: admin-enqueue-scripts].
     * 
     * @return void
     */
    function globalCSS();

    /**
     * Add global admin JS.
     * [Action: admin-enqueue-scripts].
     * 
     * @return void
     */
    function globalJS();

	/**
	 * Add admin body class.
	 * [Action: admin-body-class].
     * 
	 * @param string $classes
	 * @return string
	 */
	function addClass(string $classes) : string;

	/**
	 * Display plugin about.
	 * [Filter: admin-footer-text].
	 * 
	 * @return string
	 */
	function about();

	/**
	 * Display plugin version.
	 * [Filter: update-footer].
	 * 
	 * @return string
	 */
	function version();
}
