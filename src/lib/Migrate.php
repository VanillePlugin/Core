<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

/**
 * Plugin database manager.
 */
final class Migrate extends Orm
{
	/**
	 * @access private
	 * @var string LOCK
	 * @var string UPGRADE
	 * @var string UNINSTALL
	 * @var string ADD
	 * @var string ALTER
	 */
	private const LOCK = 'migrate.lock';
	private const UPGRADE = 'upgrade.sql';
	private const UNINSTALL = 'uninstall.sql';
	private const ADD = '/ADD\s`(.*)`\s/s';
	private const ALTER = '/ALTER\sTABLE\s`(.*)`\sADD/s';

	/**
	 * Init migrate.
	 */
	public function __construct()
	{
		// Init orm
		parent::__construct();
	}

	/**
	 * Create plugin database tables.
	 *
	 * @access public
	 * @return bool
	 */
	public function table() : bool
	{
		if ( $this->isMigrated() ) {
			return false;
		}

		if ( !($tables = $this->load()) ) {
			return false;
		}

		$count = 0;
		foreach ($tables as $table) {
			$sql = $this->readFile(
				$this->getMigratePath($table)
			);
			if ( !empty($sql) ) {
				$sql = $this->applyVars($sql);
				$count += (int)$this->execute($sql);
			}
		}

		$this->lock();
		return (bool)$count;
	}

	/**
	 * Remove plugin database tables.
	 *
	 * @access public
	 * @return bool
	 */
	public function drop() : bool
	{
		$file = $this->getMigratePath(self::UNINSTALL);
		if ( !$this->isFile($file) ) {
			return false;
		}

		if ( !empty(($sql = $this->readFile($file))) ) {
			$sql = $this->applyVars($sql);
			return (bool)$this->execute($sql);
		}

		return false;
	}

	/**
	 * Upgrade plugin database tables.
	 *
	 * @access public
	 * @return bool
	 */
	public function upgrade() : bool
	{
		if ( $this->isMigrated() ) {
			return false;
		}

		$file = $this->getMigratePath(self::UPGRADE);
		if ( !$this->isFile($file) ) {
			return false;
		}

		$count = 0;
		foreach ($this->getLines($file) as $line) {
			$sql = $this->applyVars($line);
			if ( $this->hasString($sql, 'ADD') ) {

				$column = $this->parseColumn($sql);
				$table  = $this->parseTable($sql);

				if ( !$this->hasColumn($column, $table) ) {
					$count += (int)$this->execute($sql);
				}

			} else {
				$count += (int)$this->execute($sql);
			}
		}

		return (bool)$count;
	}
	
	/**
	 * Migrate plugin options.
	 *
	 * @access public
	 * @param array $options
	 * @return bool
	 */
	public function option(array $options) : bool
	{
		$count = 0;
		foreach ($options as $old => $new) {
			$temp = $this->getOption($this->applyPrefix($old), null);
			if ( !$this->isType('null', $temp) ) {
				$count += (int)$this->updateOption($this->applyPrefix($new), $temp);
				$count += (int)$this->removeOption($this->applyPrefix($old));
			}
		}
		return (bool)$count;
	}

	/**
	 * Export plugin database table.
	 *
	 * @access public
	 * @param string $table
	 * @param mixed $column
	 * @return mixed
	 */
	public function export(string $table, $column)
	{
		$file = $this->getTempPath("{$table}.csv");
		$res = fopen($file, 'w');
		fputcsv($res, explode(',', $column), ';');
		$columns = explode(',', $column);
		foreach ($columns as $key => $value) {
			$value = trim($value);
			$columns[$key] = "`{$value}`";
		}
		$data = $this->query(new OrmQuery([
			'table'  => $table,
			'column' => $columns
		]));
		if ( empty($data) ) {
			fclose($res);
			return false;
		}
		foreach ($data as $line) {
			fputcsv($res, $line, ';');
		}
		fclose($res);
		return $file;
	}

	/**
	 * Import plugin database table.
	 *
	 * @access public
	 * @param string $table
	 * @param array $data
	 * @return bool
	 */
	public function import(string $table, array $data = []) : bool
	{
		return false;
	}

	/**
	 * Check whether plugin has migrate lock.
	 *
	 * @access public
	 * @return bool
	 */
	public function isMigrated() : bool
	{
		return $this->isFile(
			$this->getMigratePath(self::LOCK)
		);
	}

	/**
	 * Remove lock file.
	 *
	 * @access public
	 * @return bool
	 */
	public function unlock() : bool
	{
		return $this->removeFile(
			$this->getMigratePath(self::LOCK)
		);
	}

	/**
	 * Parse table name for upgrade.
	 *
	 * @access private
	 * @param string $query
	 * @return string
	 */
	private function parseTable(string $query) : string
	{
		return $this->matchString(self::ALTER, $query);
	}

	/**
	 * Parse column name for upgrade.
	 *
	 * @access private
	 * @param string $query
	 * @return string
	 */
	private function parseColumn(string $query) : string
	{
		return $this->matchString(self::ADD, $query);
	}

	/**
	 * Apply query vars.
	 *
	 * @access private
	 * @param string $sql
	 * @param string $query
	 * @return string
	 */
	private function applyVars(string $query) : string
	{
		$query = $this->replaceString('[DBPREFIX]', $this->prefix, $query);
		$query = $this->replaceString('[COLLATE]', $this->collate, $query);
		$query = $this->replaceString('[PREFIX]', $this->getPrefix(), $query);
		return $query;
	}

	/**
	 * Create lock file.
	 *
	 * @access private
	 * @return bool
	 */
	private function lock() : bool
	{
		return $this->writeFile(
			$this->getMigratePath(self::LOCK)
		);
	}

	/**
	 * Load files.
	 *
	 * @access private
	 * @return array
	 */
	private function load() : array
	{
		$path = $this->getMigratePath();
		return $this->scanDir($path, 0, [
			self::LOCK,
			self::UNINSTALL,
			self::UPGRADE
		]);
	}
}
