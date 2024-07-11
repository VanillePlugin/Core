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

namespace VanillePlugin\lib;

use VanillePlugin\int\CronInterface;

/**
 * Plugin CRON manager.
 */
class Cron implements CronInterface
{
	use \VanillePlugin\VanillePluginOption;

	/**
	 * @access protected
	 * @var array $schedules
	 * @var array $actions
	 */
	protected $schedules = [];
	protected $actions = [];

	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		foreach ($this->getCron() as $scheduler) {
			$this->addSchedulerAction($scheduler);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function apply(array $schedules) : array
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
	 * @inheritdoc
	 */
	public function start()
	{
		$this->addFilter('cron-schedules', [$this, 'apply']);

		foreach ($this->sanitizeActions() as $action) {
			$name = $this->applyNameSpace($action['name']);
			if ( !$this->next($name) ) {
				$this->schedule($action['schedule'], $name);
			}
			$this->addAction($name, $action['callable']);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function next($name)
	{
		return wp_next_scheduled($name);
	}

	/**
	 * @inheritdoc
	 */
	public function schedule(string $interval, string $hook)
	{
		return wp_schedule_event(time(), $interval, $hook);
	}

	/**
	 * @inheritdoc
	 */
	public function clear(string $name)
	{
		$name = $this->applyNameSpace($name);
		return wp_clear_scheduled_hook($name);
	}

	/**
	 * @inheritdoc
	 */
	public function remove()
	{
		foreach ($this->getCron() as $scheduler) {
			$this->clear($scheduler['name']);
		}
	}

	/**
	 * Add schedule.
	 *
	 * @access protected
	 * @param array $schedule
	 * @return void
	 */
	protected function addSchedule(array $schedule = [])
	{
		$this->schedules[] = $schedule;
	}

	/**
	 * Add scheduler action.
	 *
	 * @access protected
	 * @param array $actions
	 * @return void
	 */
	protected function addSchedulerAction(array $action = [])
	{
		$this->actions[] = $action;
	}

	/**
	 * Sanitize schedulers actions.
	 * [Filter: {plugin}-schedulers-actions].
	 *
	 * @access protected
	 * @return array
	 */
	protected function sanitizeActions()
	{
		$this->actions = $this->uniqueMultiArray(
			$this->applyPluginFilter('schedulers-actions', $this->actions)
		);

		foreach ($this->actions as $key => $action) {

			if ( !isset($action['name']) ) {
				unset($this->actions[$key]);
			}

			if ( !isset($action['schedule']) ) {
				$this->actions[$key]['schedule'] = 'daily';
			}

			if ( !isset($action['callable']) && isset($action['name']) ) {

				$callable = $this->camelcase($action['name']);
				if ( $this->hasObject('method', $this, $callable) ) {
					$this->actions[$key]['callable'] = [$this, $callable];

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
	 * Sanitize schedulers,
	 * [Filter: {plugin}-cron-schedules].
	 *
	 * @access protected
	 * @return array
	 */
	protected function sanitizeSchedules()
	{
		$this->schedules = $this->uniqueMultiArray(
			$this->applyPluginFilter('cron-schedules', $this->schedules)
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
