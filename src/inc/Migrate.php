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

use VanillePlugin\int\NameSpaceInterface;
use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\lib\PluginOptions;
use VanillePlugin\lib\Orm;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Text;

final class Migrate extends Orm // implements NameSpaceInterface
{
	/**
	 * Init Db object
	 * @param PluginNameSpaceInterface $namespace
	 * @return void
	 */
	public function __construct()
	{
		$this->init();
		$this->initConfig();
	}

	/**
	 * Create plugin tables
	 *
	 * @param void
	 * @return void
	 */
	public function table()
	{
		$dir = $this->getMigrate();
		$tables = array_diff(scandir($dir),['.','..','uninstall.sql','upgrade.sql']);

		if (!$tables) return;

		foreach ($tables as $table) {
			$installSql = File::read("{$dir}{$table}");
			if ( !empty($installSql) ) {
				$installSql = Text::replace($installSql, '[DBPREFIX]', $this->prefix);
				$installSql = Text::replace($installSql, '[PREFIX]', $this->getNameSpace());
				$installSql = Text::replace($installSql, '[COLLATE]', $this->collate);
				$this->query($installSql);
			}
		}
	}

	/**
	 * Upgrade plugin tables
	 *
	 * @param void
	 * @return void
	 */
	public function upgrade()
	{
		$dir = "{$this->getRoot()}{$this->getMigrate()}";
		// $upgradeSql = = File::read("{$dir}{$table}");
		// $upgradeSql = Text::replace($upgradeSql, '[DBPREFIX]', $this->prefix);
		// $upgradeSql = Text::replace($upgradeSql, '[PREFIX]', $this->getNameSpace());
		// $upgradeSql = Text::replace($upgradeSql, '[COLLATE]', $this->collate);
		// if ( !empty($upgradeSql) ) {
			// $this->query($upgradeSql);
		// }
	}

	/**
	 * Remove plugin tables
	 *
	 * @param void
	 * @return void
	 */
	public static function rollback()
	{
		// $config->uninstallSql = File::read("{$config->root}/core/storage/migrate/uninstall.sql");
		// $config->uninstallSql = Text::replace($config->uninstallSql, '[DBPREFIX]', $this->prefix);
		// $config->uninstallSql = Text::replace($config->uninstallSql, '[PREFIX]', $this->getNameSpace());
		// $this->query($config->uninstallSql);
	}
}
