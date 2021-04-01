<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.5.7
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\System;

class Task extends Logger
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
	 * Add item to tasklist
	 * 
	 * @access public
	 * @param string $item
	 * @return void
	 */
	public function add($item = false)
	{
		if ( $item ) {
			if ( $this->isDebug() ) {
				$this->log("Add {$item} to task");
			}
		}
		$this->setTransient('in-queue',$item);
	}

	/**
	 * Check if task exists
	 * 
	 * @access public
	 * @param string $item
	 * @return bool
	 */
	public function has($item)
	{
		if ( $this->get() == $item ) {
			if ( $this->isDebug() ) {
				$this->log("{$item} in task");
			}
			return true;
		}
		return false;
	}

	/**
	 * Get task
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public function get()
	{
		return $this->getTransient('in-queue');
	}

	/**
	 * Delete task
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function delete()
	{
		$this->setTransient('in-queue',false);
	}
}
