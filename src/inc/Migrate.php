<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.4
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use VanillePlugin\lib\Orm;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Stringify;
use VanillePlugin\int\PluginNameSpaceInterface;

final class Migrate extends Orm
{
	/**
	 * Init Db object
	 * Init Config object
	 *
	 * @param PluginNameSpaceInterface $plugin
	 * @return void
	 */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
		$this->initConfig($plugin);
		$this->init();
	}

	/**
	 * Create Plugin Tables
	 *
	 * @param void
	 * @return void
	 */
	public function table()
	{
		$tables = array_diff(scandir($this->getMigrate()),['.','..','uninstall.sql','upgrade.sql']);
		if (!$tables) return;
		foreach ($tables as $table) {
			$installSql = File::r("{$this->getMigrate()}/{$table}");
			if ( !empty($installSql) ) {
				$installSql = Stringify::replace('[DBPREFIX]', $this->prefix, $installSql);
				$installSql = Stringify::replace('[PREFIX]', $this->getPrefix(), $installSql);
				$installSql = Stringify::replace('[COLLATE]', $this->collate, $installSql);
				$this->query($installSql);
			}
		}
	}

	/**
	 * Upgrade Plugin Tables
	 *
	 * @param void
	 * @return void
	 */
	public function upgrade()
	{
		if ( !File::exists("{$this->getMigrate()}/upgrade.sql") ) return;
		$upgradeSql = File::r("{$this->getMigrate()}/upgrade.sql");
		if ( !empty($upgradeSql) ) {
			$upgradeSql = Stringify::replace('[DBPREFIX]', $this->prefix, $upgradeSql);
			$upgradeSql = Stringify::replace('[PREFIX]', $this->getPrefix(), $upgradeSql);
			$this->query($upgradeSql);
		}
	}

	/**
	 * Remove Plugin Tables
	 *
	 * @param void
	 * @return void
	 */
	public function rollback()
	{
		if ( !File::exists("{$this->getMigrate()}/uninstall.sql") ) return;
		$uninstallSql = File::r("{$this->getMigrate()}/uninstall.sql");
		if ( !empty($uninstallSql) ) {
			$uninstallSql = Stringify::replace('[DBPREFIX]', $this->prefix, $uninstallSql);
			$uninstallSql = Stringify::replace('[PREFIX]', $this->getPrefix(), $uninstallSql);
			$this->query($uninstallSql);
		}
	}
}
