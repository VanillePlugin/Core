<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\Date;

/**
 * Define date and time functions.
 */
trait TraitDatable
{
    /**
     * Get date (Default current).
     *
     * @access public
     * @inheritdoc
     */
    public function getDate(string $date = 'now', string $to = Date::FORMAT, bool $isObject = false)
    {
        return Date::get($date, $to, $isObject);
    }

    /**
     * Get date difference interval.
     *
     * @access public
     * @inheritdoc
     */
    public function getDateDiff($date, $expire, ?string $i = null, string $to = Date::FORMAT): int
    {
        return Date::difference($date, $expire, $i, $to);
    }

    /**
     * Create date object from string.
     *
     * @access public
     * @inheritdoc
     */
    public function createDate(string $date, string $format, string $to = Date::FORMAT): object
    {
        return Date::create($date, $format, $to);
    }

    /**
     * Convert date format.
     *
     * @access public
     * @inheritdoc
     */
    public function convertDate(string $date, string $format, string $to = Date::FORMAT): string
    {
        return Date::convert($date, $format, $to);
    }

    /**
     * Convert date to string format.
     *
     * @access public
     * @inheritdoc
     */
    public function dateToString($date, string $to = Date::FORMAT): string
    {
        return Date::toString($date, $to);
    }

    /**
     * Get current time.
     *
     * @access public
     * @inheritdoc
     */
    public function getTimeNow(): int
    {
        return Date::timeNow();
    }

    /**
     * Generate new time.
     *
     * @access public
     * @inheritdoc
     */
    public function newTime($h = 0, $m = 0, $s = 0, $mt = 0, $d = 0, $y = 0): int
    {
        return Date::newTime($h, $m, $s, $mt, $d, $y);
    }

    /**
     * Get date timezone.
     *
     * @access public
     * @inheritdoc
     */
    public function getDefaultTimezone(): string
    {
        return Date::getDefaultTimezone();
    }

    /**
     * Set default date timezone.
     *
     * @access protected
     * @inheritdoc
     */
    protected function setDefaultTimezone(string $timezone): bool
    {
        return Date::setDefaultTimezone($timezone);
    }
}
