<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.4.0
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\int;

interface CronInterface
{
    /**
     * @param array $schedules
     * @return void
     */
    function setSchedules($schedules = []);

    /**
     * @param array $actions
     * @return void
     */
    function setActions($actions = []);

    /**
     * Filter : cron_schedules
     *
     * @param array $schedules
     * @return void
     */
    function apply($schedules);

    /**
     * @param void
     * @return void
     */
    function start();

    /**
     * @param string $name
     * @return bool
     */
    function next($name);

    /**
     * @param int $interval
     * @param string $hook
     * @return void
     */
    function schedule($interval,$hook);

    /**
     * @param string $name
     * @return void
     */
    function clear($name);
}
