<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.3
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
				$installSql = Stringify::replace($installSql, '[DBPREFIX]', $this->prefix);
				$installSql = Stringify::replace($installSql, '[PREFIX]', $this->getPrefix());
				$installSql = Stringify::replace($installSql, '[COLLATE]', $this->collate);
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
		$file = new file();
		if ( !$file->exists("{$this->getMigrate()}/upgrade.sql") ) return;
		$upgradeSql = File::r("{$this->getMigrate()}/upgrade.sql");
		if ( !empty($upgradeSql) ) {
			$upgradeSql = Stringify::replace($upgradeSql, '[DBPREFIX]', $this->prefix);
			$upgradeSql = Stringify::replace($upgradeSql, '[PREFIX]', $this->getPrefix());
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
		$file = new file();
		if ( !$file->exists("{$this->getMigrate()}/uninstall.sql") ) return;
		$uninstallSql = File::r("{$this->getMigrate()}/uninstall.sql");
		if ( !empty($uninstallSql) ) {
			$uninstallSql = Stringify::replace($uninstallSql, '[DBPREFIX]', $this->prefix);
			$uninstallSql = Stringify::replace($uninstallSql, '[PREFIX]', $this->getPrefix());
			$this->query($uninstallSql);
		}
	}
}
