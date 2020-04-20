<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.9
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 * Allowed to edit for plugin customization
 */

namespace VanillePlugin\int;

interface RequirementInterface
{
	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function requirePlugins();

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function requireOptions();

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function requireTemplate();
}
