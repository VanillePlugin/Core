<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.5.9
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class System
{
	/**
	 * Memory exceeded
	 *
	 * @param float $percent
	 * @return bool
	 */
	public static function isMemoryOut($percent = 0.9)
	{
		$limit = self::getMemoryLimit() * $percent;
		$current = self::getMemoryUsage();
		if ( $current >= $limit ) {
			return true;
		}
		return false;
	}

	/**
	 * Get memory limit
	 *
	 * @param void
	 * @return int
	 */
	public static function getMemoryLimit()
	{
		if ( function_exists('ini_get') ) {
			$limit = ini_get('memory_limit');
		} else {
			$limit = '128M';
		}
		if ( !$limit || $limit === -1 ) {
			$limit = '32000M';
		}
		return intval($limit) * 1024 * 1024;
	}

	/**
	 * Get memory usage
	 *
	 * @param void
	 * @return int
	 */
	public static function getMemoryUsage($real = true)
	{
		return memory_get_usage($real);
	}
}
