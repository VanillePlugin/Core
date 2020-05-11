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
 */

namespace VanillePlugin\inc;

use VanillePlugin\lib\Orm;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Text;
use VanillePlugin\int\PluginNameSpaceInterface;

final class Migrate
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
			$installSql = File::read("{$this->getMigrate()}/{$table}");
			if ( !empty($installSql) ) {
				$installSql = Text::replace($installSql, '[DBPREFIX]', $this->prefix);
				$installSql = Text::replace($installSql, '[PREFIX]', $this->getPrefix());
				$installSql = Text::replace($installSql, '[COLLATE]', $this->collate);
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
		$upgradeSql = File::read("{$this->getMigrate()}/upgrade.sql");
		if ( !empty($upgradeSql) ) {
			$upgradeSql = Text::replace($upgradeSql, '[DBPREFIX]', $this->prefix);
			$upgradeSql = Text::replace($upgradeSql, '[PREFIX]', $this->getPrefix());
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
		$uninstallSql = File::read("{$this->getMigrate()}/uninstall.sql");
		if ( !empty($uninstallSql) ) {
			$uninstallSql = Text::replace($uninstallSql, '[DBPREFIX]', $this->prefix);
			$uninstallSql = Text::replace($uninstallSql, '[PREFIX]', $this->getPrefix());
			$this->query($uninstallSql);
		}
	}
}
