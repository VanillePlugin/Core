<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.5
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\int\OrmInterface;
use VanillePlugin\int\OrmQueryInterface;
use VanillePlugin\inc\Stringify;

/**
 * Helper class for database Object uses ORM query style.
 */
class Orm extends Db implements OrmInterface
{
	/**
	 * Init db object.
	 *
	 * @param void
	 */
	public function __construct()
	{
		$this->init();
	}

	/**
	 * Execute SQL query.
	 *
	 * @access public
	 * @param string $sql
	 * @return mixed
	 */
	public function query($sql)
	{
		return $this->db->query($sql);
	}

	/**
	 * Prepare SQL query.
	 *
	 * @access public
	 * @param string $sql
	 * @param mixed $args
	 * @return mixed
	 */
	public function prepare($sql, $args = [])
	{
		return $this->db->prepare($sql,$args);
	}

	/**
	 * Fetch SQL query.
	 *
	 * @access public
	 * @param string $sql
	 * @param string $type, Output type
	 * @return mixed
	 */
	public function fetchQuery($sql = null, $isRow = false, $type = 'ARRAY_A')
	{
		if ( $isRow ) {
			return $this->getRow($sql,$type);
		}
		return $this->getResult($sql,$type);
	}

	/**
	 * Custom select query.
	 *
	 * @access public
	 * @param OrmQueryInterface $data
	 * @return mixed
	 */
	public function select(OrmQueryInterface $data)
	{
		extract($data->query);
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$table = Stringify::replace('_prefix_',$prefix,$table);
		$sql  = trim("SELECT {$column} FROM {$table} {$where} {$orderby} {$limit}");
		$sql .= ';';
		if ( $isSingle ) {
			return $this->getVar($sql);

		} elseif ( $isRow ) {
			return $this->getRow($sql,$type);
		}
		return $this->getResult($sql,$type);
	}

	/**
	 * Custom select count query.
	 *
	 * @access public
	 * @param OrmQueryInterface $data
	 * @return int
	 */
	public function count(OrmQueryInterface $data)
	{
		extract($data->query);
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$table = Stringify::replace('_prefix_',$prefix,$table);
		$sql  = trim("SELECT COUNT(*) FROM {$table} {$where}");
		$sql .= ';';
		return (int)$this->getVar($sql);
	}

	/**
	 * Insert query.
	 *
	 * @access public
	 * @param string $table
	 * @param array $data
	 * @param mixed $format, Data format
	 * @return mixed
	 */
	public function insert($table, $data = [], $format = null)
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		if ( $this->db->insert("{$prefix}{$table}",$data,$format) ) {
			return $this->db->insert_id;
		}
		return false;
	}

	/**
	 * Update query.
	 *
	 * @access public
	 * @param string $table
	 * @param array $data
	 * @param array $where
	 * @param mixed $format, Data format
	 * @param mixed $f, Where format
	 * @return bool
	 */
	public function update($table, $data = [], $where = [], $format = null, $f = null)
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		return (bool)$this->db->update("{$prefix}{$table}",$data,$where,$format,$f);
	}

	/**
	 * Delete query.
	 *
	 * @access public
	 * @param string $table
	 * @param array $where
	 * @param string $format, Where format
	 * @return bool
	 */
	public function delete($table, $where = [], $format = null)
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		return (bool)$this->db->delete("{$prefix}{$table}",$where,$format);
	}

	/**
	 * Delete all content of table.
	 *
	 * @access public
	 * @param string $table
	 * @param bool $resetId
	 * @return bool
	 */
	public function deleteAll($table, $resetId = true)
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$sql = "DELETE FROM {$prefix}{$table};";
		if ( ($result = $this->query($sql)) ) {
			if ( $resetId === true ) {
				$this->resetId($table);
			}
			return (bool)$result;
		}
		return false;
	}

	/**
	 * Get variable from database.
	 *
	 * @access public
	 * @param string $sql
	 * @param int $x, Column index
	 * @param int $y, Row index
	 * @return mixed
	 */
	public function getVar($sql = null, $x = 0, $y = 0)
	{
		return $this->db->get_var($sql,$x,$y);
	}

	/**
	 * Get column from database.
	 *
	 * @access public
	 * @param string $sql
	 * @param int $x, Column index
	 * @return mixed
	 */
	public function getCol($sql = null, $x = 0)
	{
		return $this->db->get_col($sql,$x);
	}

	/**
	 * Get row from database.
	 *
	 * @access public
	 * @param string $sql
	 * @param string $type, Output type
	 * @param int $y, Row index
	 * @return mixed
	 */
	public function getRow($sql = null, $type = 'ARRAY_A', $y = 0)
	{
		return $this->db->get_row($sql,$type,$y);
	}

	/**
	 * Get result from database.
	 *
	 * @access public
	 * @param string $sql
	 * @param string $type, Output type
	 * @return mixed
	 */
	public function getResult($sql = null, $type = 'ARRAY_A')
	{
		return $this->db->get_results($sql,$type);
	}

	/**
	 * Reset table Id.
	 *
	 * @access public
	 * @param string $table
	 * @return bool
	 */
	public function resetId($table)
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$sql = "ALTER TABLE `{$prefix}{$table}` AUTO_INCREMENT = 1;";
		return (bool)$this->query($sql);
	}

	/**
	 * Get tables.
	 *
	 * @access public
	 * @param void
	 * @return array
	 */
	public function getTables()
	{
		return (array)$this->query('show tables;');
	}

	/**
	 * Check table.
	 *
	 * @access public
	 * @param string $table
	 * @return bool
	 */
	public function hasTable($table)
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$sql = "SHOW TABLES LIKE '{$prefix}{$table}';";
		return (bool)$this->query($sql);
	}

	/**
	 * Get field min.
	 *
	 * @access public
	 * @param string $table
	 * @param string $field
	 * @return mixed
	 */
	public function min($table, $field)
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$sql = "SELECT min({$field}) FROM `{$prefix}{$table}`;";
		return $this->query($sql);
	}

	/**
	 * Get field max.
	 *
	 * @access public
	 * @param string $table
	 * @param string $field
	 * @return mixed
	 */
	public function max($table, $field)
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$sql = "SELECT max({$field}) FROM `{$prefix}{$table}`;";
		return $this->query($sql);
	}

	/**
	 * Get field avg.
	 *
	 * @access public
	 * @param string $table
	 * @param string $field
	 * @return mixed
	 */
	public function avg($table = '', $field = '')
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$sql = "SELECT avg({$field}) FROM `{$prefix}{$table}`;";
		return $this->query($sql);
	}

	/**
	 * Get field sum.
	 *
	 * @access public
	 * @param string $table
	 * @param string $field
	 * @return mixed
	 */
	public function sum($table = '', $field = '')
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$sql = "SELECT sum({$field}) FROM `{$prefix}{$table}`;";
		return $this->query($sql);
	}
}
