<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface OrmInterface
{
	/**
	 * Set table field value.
	 *
	 * @param string $field
	 * @param mixed $value
	 */
	function __set(string $field, $value);

	/**
	 * Get table field value.
	 * 
	 * @param string $field
	 * @return mixed
	 */
	function __get(string $field);

	/**
	 * Disable query prepare.
	 *
	 * @return object
	 */
	function noPrepare() : self;

	/**
	 * Disable type format.
	 *
	 * @return object
	 */
	function noType() : self;

	/**
	 * Set table.
	 *
	 * @param string $table
	 * @return object
	 */
	function setTable(string $table) : self;

	/**
	 * Set key.
	 *
	 * @param string $key
	 * @return object
	 */
	function setKey(string $key) : self;

	/**
	 * Create row.
	 * Return row Id if created.
	 *
	 * @param array $data
	 * @param string $table
	 * @return mixed
	 */
	function create(array $data, ?string $table = null);

	/**
	 * Read row,
	 * Using prepare.
	 *
	 * @param mixed $id
	 * @param string $key
	 * @param string $table
	 * @return array
	 */
	function read($id = null, ?string $key = null, ?string $table = null) : array;

	/**
	 * Update rows,
	 * Return rows count.
	 *
	 * @param array $data
	 * @param array $where
	 * @param string $table
	 * @return int
	 */
	function update(array $data, array $where = [], ?string $table = null) : int;

	/**
	 * Delete rows.
	 * Return rows count.
	 * 
	 * @param array $where
	 * @param string $table
	 * @return int
	 */
	function delete(array $where = [], ?string $table = null) : int;

	/**
	 * Execute global query,
	 * Return anything.
	 * 
	 * @param string $sql
	 * @param array $data
	 * @return mixed
	 */
	function execute(string $sql, array $data = []);
	
	/**
	 * Execute result query,
	 * Return rows values.
	 * 
	 * @param string $sql
	 * @param array $data
	 * @return array
	 */
	function getResult(string $sql, array $data = []) : array;

	/**
	 * Execute field query,
	 * Return single value.
	 * 
	 * @param string $sql
	 * @param array $data
	 * @param int $x, Column index
	 * @param int $y, Row index
	 * @return mixed
	 */
	function getField(string $sql, array $data = [], int $x = 0, int $y = 0);

	/**
	 * Execute row query,
	 * Return row values.
	 * 
	 * @param string $sql
	 * @param array $data
	 * @param string $type
	 * @param int $y Row index
	 * @return array
	 */
	function getRow(string $sql, array $data = [], $y = 0) : array;

	/**
	 * Execute column query,
	 * Return column values.
	 * 
	 * @param string $sql
	 * @param array $data
	 * @param int $x, Column index
	 * @return array
	 */
	function getColumn(string $sql, array $data = [], int $x = 0) : array;

	/**
	 * Get min value.
	 *
	 * @param string $column
	 * @param string $table
	 * @return mixed
	 */
	function min(string $column, ?string $table = null);

	/**
	 * Get max value.
	 *
	 * @param string $column
	 * @param string $table
	 * @return mixed
	 */
	function max(string $column, ?string $table = null);

	/**
	 * Get avg value.
	 *
	 * @param string $column
	 * @param string $table
	 * @return mixed
	 */
	function avg(string $column, ?string $table = null);

	/**
	 * Get sum value.
	 *
	 * @param string $column
	 * @param string $table
	 * @return mixed
	 */
	function sum(string $column, ?string $table = null);
	
	/**
	 * Get all rows.
	 *
	 * @param string $table
	 * @return array
	 */
	function all(?string $table = null) : array;

	/**
	 * Count rows.
	 *
	 * @param string $table
	 * @return int
	 */
	function count(?string $table = null) : int;

    /**
     * Get last inserted Id.
     *
     * @return int
     */
    function insertId() : int;

	/**
	 * Clear table,
	 * Return rows count.
	 *
	 * @param string $table
	 * @param bool $reset
	 * @return int
	 */
	function clear(?string $table = null, bool $reset = true) : int;

	/**
	 * Reset table Ids.
	 *
	 * @param string $table
	 * @return bool
	 */
	function resetId(?string $table = null) : bool;

	/**
	 * Check database table.
	 *
	 * @param bool $wildcard
	 * @param string $table
	 * @return bool
	 */
	function hasTable(bool $wildcard = false, ?string $table = null) : bool;

	/**
	 * Check table column.
	 *
	 * @param string $column
	 * @param string $table
	 * @return bool
	 */
	function hasColumn(string $column, ?string $table = null) : bool;

	/**
	 * Get database table columns.
	 *
	 * @param string $table
	 * @return array
	 */
	function columns(?string $table = null) : array;

	/**
	 * Get database tables.
	 *
	 * @return array
	 */
	function tables() : array;

	/**
	 * Execute advanced query using query builder.
	 *
	 * @param OrmQueryInterface $query
	 * @return mixed
	 */
	function query(OrmQueryInterface $builder);
}
