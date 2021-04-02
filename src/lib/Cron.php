<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.0
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\CronInterface;

class Cron extends PluginOptions implements CronInterface
{
	/**
	* @access private
	* @var array $schedules
	* @var array $actions
	*/
	private $schedules = [];
	private $actions = [];

	/**
	 * Set schedulers
	 *
	 * @access public
	 * @param array $schedules
	 * @return void
	 */
	public function setSchedules($schedules = [])
	{
		$this->schedules = $schedules;
	}

	/**
	 * Add schedulers
	 *
	 * @access public
	 * @param array $schedules
	 * @return void
	 */
	public function addSchedules($schedules = [])
	{
		$this->schedules[] = $schedules;
	}

	/**
	 * Set actions
	 *
	 * @access public
	 * @param array $actions
	 * @return void
	 */
	public function setActions($actions = [])
	{
		$this->actions = $actions;
	}

	/**
	 * Add actions
	 *
	 * @access public
	 * @param array $actions
	 * @return void
	 */
	public function addActions($actions = [])
	{
		$this->actions[] = $actions;
	}

	/**
	 * Apply schedulers
	 * Filter : cron_schedules
	 *
	 * @access public
	 * @param array $schedules
	 * @return void
	 */
	public function apply($schedules)
	{
		foreach ($this->schedules as $schedule) {
			if ( !isset($schedules[$schedule['name']]) ) {
		        $schedules[$schedule['name']] = [
		            'display'  => $schedule['display'],
		            'interval' => $schedule['interval']
		        ];
			}
		}
	    return $schedules;
	}

	/**
	 * Start schedulers
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function start()
	{
		$this->addFilter('cron_schedules', [$this,'apply']);
		foreach ($this->actions as $action) {
			if ( !$this->next("{$this->getNameSpace()}-{$action['name']}") ) {
				$this->schedule($action['schedule'],"{$this->getNameSpace()}-{$action['name']}");
			}
			$this->addAction("{$this->getNameSpace()}-{$action['name']}",$action['callable']);
		}
	}

	/**
	 * Check scheduled waitlist
	 *
	 * @access public
	 * @param string $name
	 * @return bool
	 */
	public function next($name)
	{
		return wp_next_scheduled($name);
	}

	/**
	 * Check scheduled waitlist
	 *
	 * @access public
	 * @param int $interval
	 * @param string $hook
	 * @return void
	 */
	public function schedule($interval,$hook)
	{
		wp_schedule_event(time(),$interval,$hook);
	}

	/**
	 * Clear scheduled hook
	 *
	 * @access public
	 * @param string $name
	 * @return void
	 */
	public function clear($name)
	{
		wp_clear_scheduled_hook("{$this->getNameSpace()}-{$name}");
	}
}
