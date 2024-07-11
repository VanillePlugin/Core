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

use VanillePlugin\lib\Updater;

trait TraitUpdatable
{
	/**
	 * Set update listener.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function update(array $auth = [], array $urls = [])
	{
		(new Updater($auth, $urls))->listen();
	}

	/**
     * Get update status.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isUpdated() : bool
	{
		return (new Updater())->isUpdated();
	}

	/**
     * Set update status.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function setAsUpdated() : bool
	{
		return (new Updater())->setAsUpdated();
	}

	/**
     * Remove plugin updates.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeUpdates() : bool
	{
		return (new Updater())->remove();
	}
}
