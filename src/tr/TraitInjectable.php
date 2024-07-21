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

namespace VanillePlugin\tr;

use VanillePlugin\lib\Hook;

/**
 * Define injectable hooks functions.
 */
trait TraitInjectable
{
	/**
	 * Get hooks values.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getHookables()
	{
		return (new Hook)->getValues();
	}

	/**
	 * Register hooks inside option group.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function registerHooks()
	{
		(new Hook)->register();
	}

	/**
	 * Add hooks.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addHooks() : bool
	{
		return (new Hook)->add();
	}

	/**
	 * Update hooks values.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function updateHookables(array $data) : bool
	{
		return (new Hook)->updateValues($data);
	}
}
