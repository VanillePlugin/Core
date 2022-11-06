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

namespace VanillePlugin\lib;

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\Stringify;

class Queue extends Logger
{
    /**
     * @param PluginNameSpaceInterface $plugin
     */
    public function __construct(PluginNameSpaceInterface $plugin)
	{
		parent::__construct($plugin);
	}

	/**
	 * Add item to queue.
	 * 
	 * @access public
	 * @param string $item
	 * @param string $name
	 * @return bool
	 */
	public function add($item = '', $name = 'in')
	{
		$queue = $this->get($name);
		$queue[] = $item;
		if ( $this->isDebug(true) ) {
			$this->debug("Added '{$item}' to queue");
		}
		return $this->set($queue,$name);
	}

	/**
	 * Check if item exists.
	 * 
	 * @access public
	 * @param string $item
	 * @param string $name
	 * @return bool
	 */
	public function has($item = '', $name = 'in')
	{
		if ( Stringify::contains($this->get($name),$item) ) {
			if ( $this->isDebug(true) ) {
				$this->debug("'{$item}' in queue");
			}
			return true;
		}
		return false;
	}

	/**
	 * Delete queue.
	 * 
	 * @access public
	 * @param string $name
	 * @return void
	 */
	public function delete($name = 'in')
	{
		return $this->setTransient("{$name}-queue",[]);
	}

	/**
	 * Get queue.
	 * 
	 * @access private
	 * @param string $name
	 * @return array
	 */
	private function get($name = 'in')
	{
		if ( !($queue = $this->getTransient("{$name}-queue")) ) {
			$queue = [];
			$this->set($queue,$name);
		}
		return $queue;
	}

	/**
	 * Set queue.
	 * 
	 * @access private
	 * @param array $value
	 * @param string $name
	 * @return bool
	 */
	private function set($value = [], $name = 'in')
	{
		return $this->setTransient("{$name}-queue",$value);
	}
}
