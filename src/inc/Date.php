<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.9
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use \DateTime;

final class Date extends DateTime
{
	/**
	 * @access public
	 * @param string $date
	 * @param string $format 'd/m/Y H:i:s'
	 * @return object $date
	 */
	public static function get($date, $format = 'd/m/Y H:i:s')
	{
		$date = new self($date);
		$date->format($format);
		return $date;
	}

	/**
	 * @access public
	 * @param object $date
	 * @param object $expire
	 * @return mixed
	 */
	public static function difference($date, $expire)
	{
		$interval = $date->diff($expire)->format('%R%a');
		return intval($interval);
	}

	/**
	 * @access public
	 * @param string $date
	 * @param string $format
	 * @param string $to 'd/m/Y H:i:s'
	 * @return object $date
	 */
	public static function createFrom($date, $format, $to = 'd/m/Y H:i:s')
	{
		$date = self::createFromFormat($format, $date);
		$date->format($to);
		return $date;
	}

	/**
	 * @access public
	 * @param string $date
	 * @param string $format
	 * @param string $to 'd/m/Y H:i:s'
	 * @return string
	 */
	public static function toString($date, $format, $to = 'd/m/Y H:i:s')
	{
		return Date::createFrom($date,$format)->format($to);
	}
}
