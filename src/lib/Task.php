<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 * Allowed to edit for plugin customization
 */

namespace VanillePlugin\lib;

use VanilleNameSpace\core\system\includes\Logger;

class Task extends Settings
{
	public static $inTaskList = false;

	/**
	 * Add asin to tasklist
	 * 
	 * @param string $asin
	 * @return {inherit}
	 */
	public static function add($asin = false)
	{
		if ($asin) {
			Logger::DEBUG("Add {$asin} to task");
		}
		parent::setTransient('inQueue',$asin);
	}

	/**
	 * Get task
	 * 
	 * @param void
	 * @return {inherit}
	 */
	public static function get()
	{
		return parent::getTransient('inQueue');
	}

	/**
	 * Check if task exists
	 * 
	 * @param string $asin
	 * @return boolean
	 */
	public static function has($asin)
	{
		Logger::DEBUG("Check {$asin} in task");

		if ( self::get() == $asin ) {
			return true;
		} else return false;
	}

	/**
	 * Delete Task
	 * 
	 * @param void
	 * @return {inherit}
	 */
	public static function delete()
	{
		parent::delTransient('inQueue');
		self::add(); // Init
	}
}
