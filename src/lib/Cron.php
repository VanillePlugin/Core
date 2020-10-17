<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

class Cron extends PluginOptions
{
	/**
	* @access private
	* @var array $schedules
	* @var array $actions
	*/
	private $schedules = [];
	private $actions = [];

	/**
	 * Set Schedulers
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
	 * Set Schedulers
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
	 * Apply Schedulers
	 *
	 * @access public
	 * @param array $schedules
	 * @return void
	 *
	 * Filter : cron_schedules
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
	 * Start Cron
	 *
	 * @access protected
	 * @param void
	 * @return void
	 */
	protected function start()
	{
		$this->addFilter('cron_schedules', [$this,'apply']);
		foreach ($this->actions as $action) {
			if ( !$this->next("{$this->getNameSpace()}-{$action['name']}") ) {
				$this->schedule($action['schedule'],"{$this->getNameSpace()}-{$action['name']}");
			}
			$this->addAction("{$this->getNameSpace()}-{$action['name']}", $action['callable']);
		}
	}

	/**
	 * Check scheduled waitlist
	 *
	 * @access protected
	 * @param string $name
	 * @return boolean
	 */
	protected function next($name)
	{
		return wp_next_scheduled($name);
	}

	/**
	 * Check scheduled waitlist
	 *
	 * @access protected
	 * @param string $interval
	 * @param string $hook
	 * @return void
	 */
	protected function schedule($interval,$hook)
	{
		wp_schedule_event(time(), $interval, $hook);
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
