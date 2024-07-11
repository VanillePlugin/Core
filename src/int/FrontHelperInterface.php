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

namespace VanillePlugin\int;

interface FrontHelperInterface
{
	/**
	 * Init front helper.
	 */
	function __construct();

	/**
	 * Apply AMP.
	 * [Action: amp-css].
	 *
	 * @return void
	 */
    function amp();

	/**
	 * Front loaded.
	 * [Action: loaded].
	 *
	 * @return void
	 */
	function loaded();

	/**
	 * Front head.
	 * [Action: head].
	 *
	 * @return void
	 */
	function head();

	/**
	 * Front init.
	 * [Action: init].
	 *
	 * @return void
	 */
	function init();

	/**
	 * Plugin front data (JS).
	 * [Filter: {plugin}-front-data].
	 *
	 * @param array $data
	 * @return array
	 */
	function frontData(array $data) : array;

	/**
	 * Plugin login data (JS).
	 * [Filter: {plugin}-login-data].
	 *
	 * @param array $data
	 * @return array
	 */
	function loginData(array $data) : array;
}
