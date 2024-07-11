<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
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
	 * Init schedule,
	 * Add schedulers actions.
	 */
	function __construct();

	/**
	 * Apply schedules,
	 * [Filter: cron-schedules].
	 *
	 * @param array $schedules
	 * @return array
	 */
	function apply(array $schedules) : array;

	/**
	 * Start schedules.
	 *
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
	 * @param string $interval
	 * @param string $hook
	 * @return mixed
	 */
	function schedule(string $interval, string $hook);

	/**
	 * Clear hooked schedules.
	 *
	 * @param string $name
	 * @return mixed
	 */
	function clear(string $name);

	/**
	 * Remove schedules.
	 *
	 * @return void
	 */
	function remove();
}
