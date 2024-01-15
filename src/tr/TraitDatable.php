<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\Date;

trait TraitDatable
{
	/**
     * Get date (Default current).
     * 
	 * @access protected
	 * @inheritdoc
	 */
    protected function getDate(string $date = 'now', string $to = Date::FORMAT, bool $isObject = false)
    {
        return Date::get($date, $to, $isObject);
    }

	/**
     * Get date difference interval.
     * 
	 * @access protected
	 * @inheritdoc
	 */
    protected function getDateDiff($date, $expire, ?string $i = null, string $to = Date::FORMAT) : int
    {
        return Date::difference($date, $expire, $i, $to);
    }

    /**
     * Create date object from string.
     *
	 * @access protected
	 * @inheritdoc
     */
    protected function createDate(string $date, string $format, string $to = Date::FORMAT) : object
    {
    	return Date::create($date, $format, $to);
    }

    /**
     * Convert date format.
     * 
	 * @access protected
	 * @inheritdoc
     */
    protected function convertDate(string $date, string $format, string $to = Date::FORMAT) : string
    {
    	return Date::convert($date, $format, $to);
    }

	/**
     * Get current time.
     * 
	 * @access protected
	 * @inheritdoc
	 */
    protected function getTimeNow() : int
    {
        return Date::timeNow();
    }

	/**
     * Generate new time.
     * 
	 * @access protected
	 * @inheritdoc
	 */
    protected function newTime($h = 0, $m = 0, $s = 0, $mt = 0, $d = 0, $y = 0) : int
    {
        return Date::newTime($h, $m, $s, $mt, $d, $y);
    }

	/**
     * Set default date timezone.
     * 
	 * @access protected
	 * @inheritdoc
	 */
    protected function setDefaultTimezone(string $timezone) : bool
    {
        return Date::setDefaultTimezone($timezone);
    }

    /**
     * Get date timezone.
     * 
	 * @access protected
	 * @inheritdoc
     */
    protected function getDefaultTimezone() : string
    {
        return Date::getDefaultTimezone();
    }
}
