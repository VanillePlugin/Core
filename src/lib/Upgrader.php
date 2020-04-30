<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 * Allowed to edit for plugin customization
 */

namespace winamaz\core\system\functions\admin;

use VanillePlugin\lib\Notice;
use VanillePlugin\lib\Migrate;
use VanillePlugin\lib\VanilleCache;

class Upgrader extends Notice
{
	/**
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		// Init plugin config
		$this->initConfig();
		$this->init([$this,'check']);
	}

	/**
	 * @param void
	 * @return void
	 */
	public function check()
	{
		// Upgrade Options
		// ...

		// Remove Cache
		VanilleCache::removeAll();

		// Upgrade Database
		Migrate::upgrade();

		// Update platforms
		PlatformProvider::synchronize();
		PlatformProvider::download();

		// Reset transient
		$this->setTransient('updated', 0);

		// Set notice
		$this->render([
			'message' => $this->translateString('Winamaz Updated')
		],'admin/notice/upgrade');
	}
}
