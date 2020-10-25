<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.8
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use VanillePlugin\int\PluginNameSpaceInterface;

final class Filesystem extends File
{
	/**
	 * @access public
	 * @param array $directories
	 * @return void
	 */
	public static function create($directories = [])
	{
		foreach ($directories as $directory) {
	        if ( !self::exists($directory) ) {
	            self::addDir($directory, 0777);
	        }
		}
	}
}
