<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 * Allowed to edit for plugin customization
 */

namespace VanillePlugin\lib;

class Cron extends Wordpress
{
	/**
	* @access private
	*/
	private $schedules;

	/**
	 * Set Schedulers
	 *
	 * @access protected
	 * @param array $schedules
	 * @return void
	 */
	protected function setSchedules($schedules = [])
	{
		$this->schedules = $schedules;
	}

	/**
	 * Start Schedulers
	 *
	 * @access public
	 * @param array $schedules
	 * @return void
	 *
	 * Filter : cron_schedules
	 */
	public function start($schedules)
	{
		foreach ($this->schedules as $schedule) {
			if ( !isset($schedules[$schedule['name']]) ) {
		        $schedules[$schedule['name']] = array(
		            'display'  => $schedule['display'],
		            'interval' => $schedule['interval'],
		        );
			}
		}
	    return $schedules;
	}

	/**
	 * Clean scheduled hook
	 *
	 * @access public
	 * @param string $name
	 * @return void
	 */
	public static function clean($name)
	{
		wp_clear_scheduled_hook($name);
	}

	/**
	 * Check scheduled waitlist
	 *
	 * @access public
	 * @param string $name
	 * @return boolean
	 */
	public static function next($name)
	{
		return wp_next_scheduled($name);
	}

	/**
	 * Check scheduled waitlist
	 *
	 * @access public
	 * @param string $interval, string $hook
	 * @return void
	 */
	public static function schedule($interval,$hook)
	{
		wp_schedule_event(time(), $interval, $hook);
	}
}
