<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface RequirementInterface
{
	/**
	 * Check plugin paths.
	 * 
	 * @return void
	 */
	function requirePath();

	/**
	 * Check required plugins.
	 * 
	 * @return void
	 */
	function requirePlugins();

	/**
	 * Check required options.
	 * 
	 * @return void
	 */
	function requireOptions();

	/**
	 * Check required templates.
	 * 
	 * @return void
	 */
	function requireTemplates();

	/**
	 * Check required modules.
	 * 
	 * @return void
	 */
	function requireModules();

	/**
	 * Check required PHP version.
	 * 
	 * @return void
	 */
	function php();
}
