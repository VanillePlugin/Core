<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface FrontInterface
{
	/**
	 * Setup plugin front.
	 *
	 * @param ShortcodeInterface $shortcode
	 * @uses !isAdmin()
	 * @uses !isLogin()
	 */
	function __construct(?ShortcodeInterface $shortcode = null);

	/**
	 * Init plugin front.
	 * [Action: wp].
	 *
	 * @return void
	 * @uses !AMP::isActive()
	 */
	function init();

	/**
	 * Add front plugin CSS.
	 * [Action: wp-enqueue-scripts].
	 *
	 * @return void
	 */
	function initCSS();

	/**
	 * Add front plugin JS.
	 * [Action: wp-enqueue-scripts].
	 * 
	 * @return void
	 */
	function initJS();

	/**
	 * Add front body class.
	 * [Filter: body-class].
	 * 
	 * @param array $classes
	 * @return array
	 */
	function addClass(array $classes) : array;
}
