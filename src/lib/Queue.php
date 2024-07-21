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

/**
 * Plugin queue manager.
 */
class Queue
{
    use \VanillePlugin\VanillePluginConfig,
        \VanillePlugin\tr\TraitCacheable;

	/**
	 * Add item to queue.
	 *
	 * @access public
	 * @param string $item
	 * @param string $name
	 * @return bool
	 */
	public function add(string $item, string $name = 'in') : bool
	{
		$queue = $this->get($name);
		$queue[] = $item;
		return $this->set($queue, $name);
	}

	/**
	 * Check whether item in queue.
	 *
	 * @access public
	 * @param string $item
	 * @param string $name
	 * @return bool
	 */
	public function has(string $item, string $name = 'in') : bool
	{
		return $this->inArray($item, $this->get($name)) ;
	}

	/**
	 * Delete queue.
	 *
	 * @access public
	 * @param string $name
	 * @return bool
	 */
	public function delete(string $name = 'in') : bool
	{
		$name = $this->applyNamespace("{$name}-queue");
		return $this->setTransient($name, []);
	}

	/**
	 * Get queue.
	 *
	 * @access private
	 * @param string $name
	 * @return array
	 */
	private function get(string $name = 'in') : array
	{
		$name = $this->applyNamespace("{$name}-queue");
		if ( !($queue = $this->getTransient($name)) ) {
			$queue = [];
			$this->set($queue, $name);
		}
		return $queue;
	}

	/**
	 * Set queue.
	 *
	 * @access private
	 * @param array $queue
	 * @param string $name
	 * @return bool
	 */
	private function set(array $queue = [], string $name = 'in') : bool
	{
		$name = $this->applyNamespace("{$name}-queue");
		return $this->setTransient($name, $queue);
	}
}
