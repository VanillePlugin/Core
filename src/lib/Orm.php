<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.2
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\OrmInterface;
use VanillePlugin\int\OrmQueryInterface;
use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\Stringify;

class Orm extends Db implements OrmInterface
{
	/**
	 * Init Db object.
	 *
	 * @param void
	 */
	public function __construct()
	{
		$this->init();
	}

	/**
	 * Custom SQL query.
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
	 * SQL query prepare.
	 *
	 * @access public
	 * @param string $sql
	 * @param mixed $args
	 * @return mixed
	 */
	public function prepare($sql, $args)
	{
		return $this->db->prepare($sql,$args);
	}

	/**
	 * Fetch SQL query.
	 *
	 * @access public
	 * @param string $sql
	 * @param string $type
	 * @return mixed
	 */
	public function fetchQuery($sql, $isRow = false, $type = 'ARRAY_A')
	{
		if ( $isRow ) {
			return $this->getRow($sql,$type);
		}
		return $this->getResult($sql,$type);
	}

	/**
	 * Select query.
	 *
	 * @access public
	 * @param OrmQueryInterface $data
	 * @return mixed
	 */
	public function select(OrmQueryInterface $data)
	{
		extract($data->query);
		if ( $column !== '*' ) {
			if ( Stringify::contains($column,',') ) {
				$exceptions = ['`','COUNT','CONCAT'];
				if ( !Stringify::contains($exceptions,$column) ) {
					$columns = explode(',',$column);
					foreach($columns as $key => $value) {
						$value = trim($value);
						$columns[$key] = "`{$value}`";
					}
					$column = implode(',',$columns);
				}
			}
		}
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$sql  = trim("SELECT $column FROM `{$prefix}{$table}` {$where} {$orderby} {$limit}");
		$sql .= ';';
		if ( $isSingle ) {
			return $this->getVar($sql);
		} elseif ( $isRow ) {
			return $this->getRow($sql,$type);
		}
		return $this->getResult($sql,$type);
	}

	/**
	 * Select count query.
	 *
	 * @access public
	 * @param OrmQueryInterface $data
	 * @return int
	 */
	public function count(OrmQueryInterface $data)
	{
		extract($data->query);
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$sql = "SELECT COUNT(*) FROM {$prefix}{$table} {$where}";
		return (int)$this->getVar($sql);
	}

	/**
	 * Insert query.
	 *
	 * @access public
	 * @param string $table
	 * @param array $data
	 * @param mixed $format false
	 * @return mixed
	 */
	public function insert($table, $data = [], $format = false)
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
	 * @param mixed $format false
	 * @return bool
	 */
	public function update($table, $data = [], $where = [], $format = false)
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		return (bool)$this->db->update("{$prefix}{$table}",$data,$where,$format);
	}

	/**
	 * Delete all query.
	 *
	 * @access public
	 * @param string $table
	 * @param bool $resetId
	 * @return bool
	 */
	public function deleteAll($table, $resetId = true)
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$sql = "DELETE FROM {$prefix}{$table}";
		if ( ($result = $this->db->query($sql)) ) {
			if ( $resetId ) {
				$sql = "ALTER TABLE {$prefix}{$table} AUTO_INCREMENT = 1";
				$this->db->query($sql);
			}
			return (bool)$result;
		}
		return false;
	}

	/**
	 * Delete query.
	 *
	 * @access public
	 * @param string $table
	 * @param array $where
	 * @param string $format null
	 * @return bool
	 */
	public function delete($table, $where = [], $format = null)
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		return (bool)$this->db->delete("{$prefix}{$table}",$where,$format);
	}

	/**
	 * Get query var.
	 *
	 * @access public
	 * @param string $sql
	 * @return mixed
	 */
	public function getVar($sql)
	{
		return $this->db->get_var($sql);
	}

	/**
	 * Get query row.
	 *
	 * @access public
	 * @param string $sql
	 * @param string $type
	 * @return mixed
	 */
	public function getRow($sql, $type = 'ARRAY_A')
	{
		return $this->db->get_row($sql,$type);
	}

	/**
	 * Get query result.
	 *
	 * @access public
	 * @param string $sql
	 * @param string $type
	 * @return mixed
	 */
	public function getResult($sql, $type = 'ARRAY_A')
	{
		return $this->db->get_results($sql,$type);
	}

	/**
	 * Reset table Id.
	 *
	 * @access public
	 * @param string $table
	 * @return mixed
	 */
	public function resetId($table = '')
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$sql = "ALTER TABLE `{$prefix}{$table}` AUTO_INCREMENT = 1;";
		return $this->db->query($sql);
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
		return (array)$this->db->query('show tables;');
	}

	/**
	 * Check table.
	 *
	 * @access public
	 * @param string $table
	 * @return bool
	 */
	public function hasTable($table = '')
	{
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		$sql = "SHOW TABLES LIKE '{$prefix}{$table}';";
		return (bool)$this->db->query($sql);
	}
}
