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

namespace VanillePlugin\int;

interface CronInterface
{
    /**
     * Apply schedulers.
     * Filter: cron_schedules
     *
     * @param array $schedules
     * @return array
     */
    function apply($schedules);

    /**
     * Start schedulers.
     *
     * @param void
     * @return void
     */
    function start();

    /**
     * Check scheduled waitlist.
     *
     * @param string $name
     * @return bool
     */
    function next($name);

    /**
     * Check scheduled waitlist.
     *
     * @param int $interval
     * @param string $hook
     * @return mixed
     */
    function schedule($interval,$hook);

    /**
     * Clear hooked schedulers.
     *
     * @param string $name
     * @return mixed
     */
    function clear($name);
}
