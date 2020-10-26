<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.0
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\OrmInterface;
use VanillePlugin\int\OrmQueryInterface;
use VanillePlugin\int\PluginNameSpaceInterface;

class Orm extends Db implements OrmInterface
{
	/**
	 * Init Db object
	 *
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$this->init();
	}

	/**
	 * Custom SQL Query
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
	 * Fetch SQL Query
	 *
	 * @access public
	 * @param string $sql
	 * @param string $type
	 * @return mixed
	 */
	public function fetchQuery($sql, $type = 'ARRAY_A')
	{
		return $this->db->get_results($sql, $type);
	}

	/**
	 * Select Query
	 *
	 * @access public
	 * @param OrmQueryInterface $data
	 * @return mixed
	 */
	public function select(OrmQueryInterface $data)
	{
		extract($data->query);
		$sql = "SELECT {$column} FROM {$this->prefix}{$this->getPrefix()}{$table} {$where} {$orderby} {$limit}";
		if ($isSingle) {
			return $this->db->get_var($sql);
		} elseif ($isRow) {
			return $this->db->get_row($sql, $type);
		} else {
			return $this->db->get_results($sql, $type);
		}
	}

	/**
	 * Select Count Query
	 *
	 * @access public
	 * @param OrmQueryInterface $data
	 * @return int
	 */
	public function count(OrmQueryInterface $data)
	{
		extract($data->query);
		$sql = "SELECT COUNT(*) FROM {$this->prefix}{$this->getPrefix()}{$table} {$where}";
		return intval($this->db->get_var($sql));
	}

	/**
	 * Insert Query
	 *
	 * @access public
	 * @param string $table
	 * @param array $data
	 * @param mixed $format false
	 * @return mixed 
	 */
	public function insert($table, $data = [], $format = false)
	{
		$this->db->insert("{$this->prefix}{$this->getPrefix()}{$table}", $data, $format);
		return $this->db->insert_id;
	}

	/**
	 * Update Query
	 *
	 * @access public
	 * @param string $table
	 * @param array $data
	 * @param array $where
	 * @param mixed $format false
	 * @return mixed
	 */
	public function update($table, $data = [], $where = [], $format = false)
	{
		return $this->db->update("{$this->prefix}{$this->getPrefix()}{$table}", $data, $where, $format);
	}

	/**
	 * Delete All Query
	 *
	 * @access public
	 * @param string $table
	 * @return int
	 */
	public function deleteAll($table)
	{
		$sql = "DELETE FROM {$this->prefix}{$this->getPrefix()}{$table}";
		return $this->db->query($sql);
	}

	/**
	 * Delete Query
	 *
	 * @access public
	 * @param string $table
	 * @param array $where
	 * @param string $format null
	 * @return int
	 */
	public function delete($table, $where = [], $format = null)
	{
		return $this->db->delete("{$this->prefix}{$this->getPrefix()}{$table}", $where, $format);
	}
}
