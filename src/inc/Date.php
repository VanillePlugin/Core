<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.2
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

use \DateTime;
use \DateInterval;

final class Date extends DateTime
{
	/**
	 * @access public
	 * @param string $date
	 * @param string $format
	 * @return mixed
	 */
	public static function get($date = 'now', $format = 'Y-m-d H:i:s', $object = false)
	{
		$date = new self($date);
        if ( $object ) {
            $date->format($format);
            return $date;
        }
        return $date->format($format);
	}

    /**
     * @access public
     * @param mixed $date
     * @param mixed $expire
     * @param string $format
     * @return int
     */
    public static function difference($date, $expire, $format = null)
    {
        if ( TypeCheck::isString($date) ) {
            $date = new self($date);
        }
        if ( TypeCheck::isString($expire) ) {
            $expire = new self($expire);
        }
        if ( $format ) {
            // '%R%a'
            $interval = $date->diff($expire)->format($format);
        } else {
            $interval = $expire->getTimestamp() - $date->getTimestamp();
        }
        return intval($interval);
    }

	/**
	 * @access public
	 * @param string $date
	 * @param string $format
	 * @return object
	 */
	public static function create($date, $format = 'Y-m-d H:i:s')
	{
		return self::createFromFormat($format,$date);
	}

	/**
	 * @access public
	 * @param string $date
	 * @param string $format
	 * @param string $to
	 * @return string
	 */
	public static function toString($date, $format, $to = 'Y-m-d H:i:s')
	{
        $datetime = self::create($date,$format)->format($to);
        return self::i18n($datetime,$to);
	}

    /**
     * @access public
     * @param string $date
     * @param string $format
     * @param string $gmt
     * @return string
     */
    public static function i18n($date, $format = 'Y-m-d H:i:s', $gmt = false)
    {
        return date_i18n($format,strtotime($date),$gmt);
    }
    
    /**
     * Return current time
     *
     * @access public
     * @param void
     * @return int
     */
    public static function timeNow()
    {
        $currentHour = date('H');
        $currentMin  = date('i');
        $currentSec  = date('s');
        $currentMon  = date('m');
        $currentDay  = date('d');
        $currentYear = date('y');
        return mktime(
            (int)$currentHour,
            (int)$currentMin,
            (int)$currentSec,
            (int)$currentMon,
            (int)$currentDay,
            (int)$currentYear
        );
    }

    /**
     * Generates new time
     *
     * @access public
     * @param int $h
     * @param int $m
     * @param int $s
     * @param int $mt
     * @param int $d
     * @param int $y
     * @return int
     */
    public static function newTime($h = 0, $m = 0, $s = 0, $mt = 0, $d = 0, $y = 0)
    {
        $currentHour = date('H');
        $currentMin  = date('i');
        $currentSec  = date('s');
        $currentMon  = date('m');
        $currentDay  = date('d');
        $currentYear = date('y');
        return mktime(
            ($currentHour + $h),
            ($currentMin + $m),
            ($currentSec + $s),
            ($currentMon + $mt),
            ($currentDay + $d),
            ($currentYear + $y)
        );
    }

    /**
     * @access public
     * @param string $duration
     * @param string $date
     * @return int
     */
    public static function expireIn($duration = 'P1Y', $date = 'now')
    {
        $date = new self($date);
        $now = mktime(
            (int)$date->format('H'),
            (int)$date->format('i'),
            (int)$date->format('s'),
            (int)$date->format('m'),
            (int)$date->format('d'),
            (int)$date->format('Y')
        );
        $expire = $date->add(new DateInterval($duration));
        $limit = mktime(
            (int)$expire->format('H'),
            (int)$expire->format('i'),
            (int)$expire->format('s'),
            (int)$expire->format('m'),
            (int)$expire->format('d'),
            (int)$expire->format('Y')
        );
        return (int)$limit - $now;
    }

    /**
     * @access public
     * @param string $timezone
     * @return void
     */
    public static function setDefaultTimezone($timezone = '')
    {
        date_default_timezone_set($timezone);
    }
}
