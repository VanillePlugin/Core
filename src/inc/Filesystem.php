<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.9
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

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
	            self::addDir($directory);
	        }
		}
	}
}
