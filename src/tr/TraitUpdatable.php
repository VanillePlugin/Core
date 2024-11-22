<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\int\UpgraderInterface;
use VanillePlugin\lib\Updater;

/**
 * Define updating and upgrading functions.
 */
trait TraitUpdatable
{
	/**
     * Get update status.
     *
	 * @access public
	 * @inheritdoc
	 */
	public function isUpdated() : bool
	{
		return (new Updater())->isUpdated();
	}

	/**
	 * Set update listener.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function doUpdate(array $auth = [], array $urls = [])
	{
		(new Updater($auth, $urls))->listen();
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

	/**
     * Set upgrader listener.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function doUpgrade(UpgraderInterface $upgrader)
	{
		if ( $this->isUpdated() ) {
			$upgrader->upgrade();
		}
	}
}
