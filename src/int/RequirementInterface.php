<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.1
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
	 * Check plugin cache path.
	 * 
	 * @param void
	 * @return void
	 */
	function requirePath();

	/**
	 * @param void
	 * @return void
	 */
	function requirePlugins();

	/**
	 * @param void
	 * @return void
	 */
	function requireOptions();

	/**
	 * @param void
	 * @return void
	 */
	function requireTemplates();
	
	/**
	 * @param void
	 * @return void
	 */
	function requireModules();

	/**
	 * @param void
	 * @return void
	 */
	function php();
}
