<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.5.4
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
	 * @param string $format
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
	 * @param string $to
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
	 * @param string $to
	 * @return string
	 */
	public static function toString($date, $format, $to = 'd/m/Y H:i:s')
	{
		return Date::createFrom($date,$format)->format($to);
	}
}
