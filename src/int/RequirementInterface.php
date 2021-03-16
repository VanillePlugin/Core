<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.4.5
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\int;

interface RequirementInterface
{
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
