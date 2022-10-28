<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\int\CronInterface;
use VanillePlugin\inc\Arrayify;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\TypeCheck;

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
	 * Apply schedulers.
	 * Filter: cron_schedules
	 *
	 * @access public
	 * @param array $schedules
	 * @return array
	 */
	public function apply($schedules)
	{
		foreach ($this->sanitizeSchedules() as $schedule) {
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
	 * Start schedulers.
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function start()
	{
		$this->addFilter('cron_schedules', [$this,'apply']);
		foreach ($this->sanitizeActions() as $action) {
			if ( !$this->next("{$this->getNameSpace()}-{$action['name']}") ) {
				$this->schedule(
					$action['schedule'],
					"{$this->getNameSpace()}-{$action['name']}"
				);
			}
			$this->addAction("{$this->getNameSpace()}-{$action['name']}",$action['callable']);
		}
	}

	/**
	 * Check scheduled waitlist.
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
	 * Check scheduled waitlist.
	 *
	 * @access public
	 * @param int $interval
	 * @param string $hook
	 * @return mixed
	 */
	public function schedule($interval,$hook)
	{
		return wp_schedule_event(time(),$interval,$hook);
	}

	/**
	 * Clear hooked schedulers.
	 *
	 * @access public
	 * @param string $name
	 * @return mixed
	 */
	public function clear($name)
	{
		return wp_clear_scheduled_hook("{$this->getNameSpace()}-{$name}");
	}

	/**
	 * Set schedulers.
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
	 * Add schedulers.
	 *
	 * @access protected
	 * @param array $schedules
	 * @return void
	 */
	protected function addSchedules($schedules = [])
	{
		$this->schedules[] = $schedules;
	}

	/**
	 * Set schedulers actions.
	 *
	 * @access protected
	 * @param array $actions
	 * @return void
	 */
	protected function setActions($actions = [])
	{
		$this->actions = $actions;
	}

	/**
	 * Add schedulers actions.
	 *
	 * @access protected
	 * @param array $actions
	 * @return void
	 */
	protected function addActions($actions = [])
	{
		$this->actions[] = $actions;
	}

	/**
	 * Sanitize schedulers actions.
	 * Filter: {plugin}-schedulers-actions
	 *
	 * @access protected
	 * @param void
	 * @return array
	 */
	protected function sanitizeActions()
	{
		$this->actions = Arrayify::uniqueMultiple(
			$this->applyPluginFilter('schedulers-actions',$this->actions)
		);
		foreach ($this->actions as $key => $action) {
			if ( !isset($action['name']) ) {
				unset($this->actions[$key]);
			}
			if ( !isset($action['schedule']) ) {
				$this->actions[$key]['schedule'] = 'daily';
			}
			if ( !isset($action['callable']) && isset($action['name']) ) {
				$name = explode('-',Stringify::slugify($action['name']));
				if ( count($name) == 2 ) {
					$name[1] = Stringify::capitalize($name[1]);
				}
				$callable = implode('',$name);
				$callable = Stringify::replace('-','',$callable);
				if ( TypeCheck::hasMethod($this,$callable) ) {
					$this->actions[$key]['callable'] = [$this,$callable];
				} else {
					unset($this->actions[$key]);
				}
			} else {
				unset($this->actions[$key]);
			}
		}
		return $this->actions;
	}

	/**
	 * Sanitize schedulers.
	 * Filter: {plugin}-cron-schedules
	 *
	 * @access protected
	 * @param void
	 * @return array
	 */
	protected function sanitizeSchedules()
	{
		$this->schedules = Arrayify::uniqueMultiple(
			$this->applyPluginFilter('cron-schedules',$this->schedules)
		);
		foreach ($this->schedules as $key => $schedule) {
			if ( !isset($schedule['name']) 
			  || !isset($schedule['display']) 
			  || !isset($schedule['interval']) ) {
				unset($this->schedules[$key]);
			} else {
				$this->schedules[$key]['interval'] = (int)$schedule['interval'];
			}
		}
		return $this->schedules;
	}
}
