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
	 * Init event timestamp.
     *
     * @param int $timestamp
	 */
	function __construct(?int $timestamp = null);

	/**
	 * Register events.
	 * [Action: {plugin}-load].
	 * [Filter: cron-schedules].
	 *
	 * @return void
	 */
	function register();

	/**
	 * Run scheduled events.
	 * [Action: init].
	 *
	 * @return void
	 */
	function run();

	/**
	 * Remove scheduled events.
	 * [Action: {plugin}-deactivate].
	 *
	 * @return void
	 */
	function remove();

	/**
	 * Use server cron.
	 * [Action: {plugin}-load].
	 *
	 * @return void
	 */
	function useServer();

	/**
	 * Check server cron handler.
	 * [Action: {plugin}-load].
	 *
	 * @return bool
	 */
	function isServer() : bool;

	/**
	 * Get next event timestamp.
	 *
	 * @param string $name
	 * @param array $args
	 * @return int
	 */
	function next(string $name, array $args = []) : int;

	/**
	 * Schedule recurring event.
	 *
	 * @param string $interval
	 * @param string $hook
	 * @param array $args
	 * @return bool
	 */
	function schedule(string $interval, string $hook, array $args = []) : bool;

	/**
	 * Schedule event once.
	 *
	 * @param string $hook
	 * @param array $args
	 * @return bool
	 */
	function once(string $hook, array $args = []) : bool;

	/**
	 * Unschedule scheduled event.
	 *
	 * @param string $hook
	 * @param array $args
	 * @return bool
	 */
	function unschedule(string $hook, array $args = []) : bool;

	/**
	 * Clear schedule event.
	 * [Action: {plugin}-deactivate].
	 *
	 * @param string $hook
	 * @param array $args
	 * @return int
	 */
	function clear(string $hook, array $args = []) : int;
}
