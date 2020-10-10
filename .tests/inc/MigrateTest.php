<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.6
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePluginTest\inc;

use VanillePluginTest\lib\OrmTest;
use VanillePluginTest\inc\FileTest;
use VanillePluginTest\inc\StringifyTest;
use VanillePluginTest\int\PluginNameSpaceInterfaceTest;

final class MigrateTest extends OrmTest
{
	/**
	 * Init Db object
	 * Init Config object
	 *
	 * @param PluginNameSpaceInterface $plugin
	 * @return void
	 */
	public function __construct(PluginNameSpaceInterfaceTest $plugin)
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
			$installSql = FileTest::r("{$this->getMigrate()}/{$table}");
			if ( !empty($installSql) ) {
				$installSql = StringifyTest::replace('[DBPREFIX]', $this->prefix, $installSql);
				$installSql = StringifyTest::replace('[PREFIX]', $this->getPrefix(), $installSql);
				$installSql = StringifyTest::replace('[COLLATE]', $this->collate, $installSql);
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
		if ( !FileTest::exists("{$this->getMigrate()}/upgrade.sql") ) return;
		$upgradeSql = FileTest::r("{$this->getMigrate()}/upgrade.sql");
		if ( !empty($upgradeSql) ) {
			$upgradeSql = StringifyTest::replace('[DBPREFIX]', $this->prefix, $upgradeSql);
			$upgradeSql = StringifyTest::replace('[PREFIX]', $this->getPrefix(), $upgradeSql);
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
		if ( !FileTest::exists("{$this->getMigrate()}/uninstall.sql") ) return;
		$uninstallSql = FileTest::r("{$this->getMigrate()}/uninstall.sql");
		if ( !empty($uninstallSql) ) {
			$uninstallSql = StringifyTest::replace('[DBPREFIX]', $this->prefix, $uninstallSql);
			$uninstallSql = StringifyTest::replace('[PREFIX]', $this->getPrefix(), $uninstallSql);
			$this->query($uninstallSql);
		}
	}
}
