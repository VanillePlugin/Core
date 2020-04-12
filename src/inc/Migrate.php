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

namespace winamaz\core\system\includes;

use winamaz\core\system\libraries\Orm;

class Migrate
{
	/**
	 * Create plugin tables
	 *
	 * @param void
	 * @return void
	 */
	public static function table()
	{
		$orm = new Orm();
		$config = new Config();

		$baseDir = "{$config->root}/core/storage/migrate/";
		$tables = array_diff(scandir($baseDir),['.','..','uninstall.sql','upgrade.sql']);

		foreach ($tables as $table) {

			$config->installSql = File::read("{$baseDir}{$table}");
			$config->installSql = Text::replace($config->installSql, '[DBPREFIX]', $orm->prefix);
			$config->installSql = Text::replace($config->installSql, '[PREFIX]', $orm->basePrefix);
			$config->installSql = Text::replace($config->installSql, '[COLLATE]', $orm->collate);
			$orm->query($config->installSql);
		}
	}

	/**
	 * Upgrade plugin tables
	 *
	 * @param void
	 * @return void
	 */
	public static function upgrade()
	{
		$orm = new Orm();
		$config = new Config();

		$config->installSql = File::read("{$config->root}/core/storage/migrate/upgrade.sql");
		$config->installSql = Text::replace($config->installSql, '[DBPREFIX]', $orm->prefix);
		$config->installSql = Text::replace($config->installSql, '[PREFIX]', $orm->basePrefix);
		$config->installSql = Text::replace($config->installSql, '[COLLATE]', $orm->collate);
		if ( !empty($config->installSql) ) {
			$orm->query($config->installSql);
		}
	}

	/**
	 * Remove plugin tables
	 *
	 * @param void
	 * @return void
	 */
	public static function rollback()
	{
		$orm = new Orm();
		$config = new Config();

		$config->uninstallSql = File::read("{$config->root}/core/storage/migrate/uninstall.sql");
		$config->uninstallSql = Text::replace($config->uninstallSql, '[DBPREFIX]', $orm->prefix);
		$config->uninstallSql = Text::replace($config->uninstallSql, '[PREFIX]', $orm->basePrefix);
		$orm->query($config->uninstallSql);
	}
}
