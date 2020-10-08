<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.6
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use \DateTime;

final class Date
{
	/**
	 * @access public
	 * @param string $date
	 * @param string $format
	 * @return object
	 */
	public static function get($date, $format = 'm/d/Y H:i:s')
	{
		$date = new DateTime($date);
		$date->format($format);
		return $date;
	}

	/**
	 * @access public
	 * @param object $date
	 * @param object $expire
	 * @return int
	 */
	public static function difference($date, $expire)
	{
		$interval = $date->diff($expire)->format('%R%a');
		return intval($interval);
	}

	/**
	 * @access public
	 * @param string $format
	 * @param string $string
	 * @param string $to
	 * @return object $date
	 */
	public static function from($format, $string, $to = 'm/d/Y H:i:s')
	{
		$date = DateTime::createFromFormat($format, $string);
		$date->format($to);
		return $date;
	}

	/**
	 * @access public
	 * @param string $date
	 * @param string $format
	 * @return object
	 */
	public static function toString($date, $format = 'M/d/Y H:i:s', $to = 'd/m/Y H:i:s')
	{
		if ( is_string($date) ) {
			$date = self::from($format,$date,$to);
		} else {
			$date = self::get($date, $to);
		}
		return $date->format($to);
	}
}
