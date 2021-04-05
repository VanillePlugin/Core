<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.2
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\System;
use VanillePlugin\inc\Stringify;

class Queue extends Logger
{
    /**
     * @param PluginNameSpaceInterface $plugin
     */
    public function __construct(PluginNameSpaceInterface $plugin)
	{
        // Init plugin config
        $this->initConfig($plugin);
        if ( System::isMemoryOut() ) {
        	die();
        }
	}

	/**
	 * Add item to queue
	 * 
	 * @access public
	 * @param string $item
	 * @param string $name
	 * @return void
	 */
	public function add($item, $name = 'in')
	{
		if ( $item ) {
			if ( $this->isDebug(true) ) {
				$this->log("Add {$item} to queue");
			}
			$queue = $this->get("{$name}-queue");
			$queue[] = $item;
			$this->setTransient("{$name}-queue",$queue);
		}
	}

	/**
	 * Check if item exists
	 * 
	 * @access public
	 * @param string $item
	 * @param string $name
	 * @return bool
	 */
	public function has($item, $name = 'in')
	{
		if ( Stringify::contains($this->get("{$name}-queue"), $item) ) {
			if ( $this->isDebug(true) ) {
				$this->log("{$item} in queue");
			}
			return true;
		}
		return false;
	}

	/**
	 * Get queue
	 * 
	 * @access public
	 * @param string $name
	 * @return mixed
	 */
	public function get($name = 'in')
	{
		$queue = $this->getTransient("{$name}-queue");
		return ($queue) ? $queue : [];
	}

	/**
	 * Delete queue 
	 * 
	 * @access public
	 * @param string $item
	 * @param string $name
	 * @return void
	 */
	public function delete($item = false, $name = 'in')
	{
		$queue = $this->get("{$name}-queue");
		if ( $item && isset($queue[$item]) ) {
			unset($queue[$item]);
		} else {
			array_pop($queue);
		}
		$this->setTransient('in-queue',$queue);
	}

	/**
	 * Clear queue
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function clear()
	{
		$this->setTransient('in-queue',[]);
	}
}
