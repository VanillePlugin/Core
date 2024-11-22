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

namespace VanillePlugin\int;

interface FrontInterface
{
	/**
	 * Setup plugin front.
	 * [Action: plugins-loaded].
	 * [Uses: !isAdmin()].
	 *
	 * @param ShortcodeInterface $shortcode
	 */
	function __construct(?ShortcodeInterface $shortcode = null);

	/**
	 * Init front.
	 * [Action: front-init].
	 * [Action: front-init].
	 * [Uses: !AMP::isEnabled()].
	 *
	 * @return void
	 */
	function init();

	/**
	 * Init amp.
	 * [Action: setup].
	 * [Uses: !AMP::isActive()].
	 *
	 * @return void
	 */
	function amp();

	/**
	 * Add front CSS.
	 * [Action: enqueue-scripts].
	 *
	 * @return void
	 */
	function initCSS();

	/**
	 * Add front JS.
	 * [Action: enqueue-scripts].
	 *
	 * @return void
	 */
	function initJS();

	/**
	 * Init login CSS.
	 * [Action: login-enqueue-scripts].
	 *
	 * @return void
	 * @uses isLogin()
	 */
	function loginCSS();

	/**
	 * Add login JS.
	 * [Action: login-enqueue-scripts].
	 *
	 * @return void
	 */
	function loginJS();

	/**
	 * Add front body class.
	 * [Filter: body-class].
	 * [Filter: login-body-class].
	 *
	 * @param array $classes
	 * @return array
	 */
	function addClass(array $classes) : array;
}
