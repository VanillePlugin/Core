<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\lib\Migrate;

trait TraitMigratable
{
	/**
	 * Create plugin database tables.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function migrateTables() : bool
	{
		return (new Migrate())->table();
	}

	/**
	 * Remove plugin database tables.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected static function dropTables() : bool
	{
		return (new Migrate())->drop();
	}

	/**
	 * Upgrade plugin database table(s).
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function upgradeTables() : bool
	{
		return (new Migrate())->upgrade();
	}

	/**
	 * Migrate plugin options.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function migrateOptions(array $options) : bool
	{
		return (new Migrate())->option($options);
	}

	/**
	 * Check whether plugin has migrate lock.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isMigrated() : bool
	{
		return (new Migrate())->isMigrated();
	}

	/**
	 * Export plugin database table.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function exportTable(string $table, $column)
	{
		return (new Migrate())->export($table, $column);
	}

	/**
	 * Import plugin database table.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function importTable(string $table, array $data) : bool
	{
		return (new Migrate())->import($table, $data);
	}

	/**
	 * Clear plugin database table.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function clearTable(string $table) : bool
	{
		return (bool)(new Migrate())->clear($table);
	}
}
