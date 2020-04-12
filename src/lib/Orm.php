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

namespace VanillePlugin\lib;

use VanillePlugin\int\OrmInterface;

class Orm extends Db implements OrmInterface
{
	/**
	 * init Db object
	 *
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		self::init(new Config);
	}

	/**
	 * Custom Query
	 *
	 * @access public
	 * @param string $sql
	 * @param boolean $return
	 * @return int
	 */
	public function query($sql, $return = false, $type = 'ARRAY_A')
	{
		if ($return) {
			return $this->db->get_results($sql,$type);
		} else return $this->db->query($sql);
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
		$sql = "SELECT {$column} FROM {$this->prefix}{$this->basePrefix}{$table} {$where} {$orderby} {$limit}";
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
		$sql = "SELECT COUNT(*) FROM {$this->prefix}{$this->basePrefix}{$table} {$where}";
		return intval($this->db->get_var($sql));
	}

	/**
	 * Insert Query
	 *
	 * @access public
	 * @param string $table
	 * @param array $data
	 * @param array|string $format
	 * @return int|false
	 */
	public function insert($table, $data = [], $format = false)
	{
		$this->db->insert("{$this->prefix}{$this->basePrefix}{$table}", $data, $format);
		return $this->db->insert_id;
	}

	/**
	 * Update Query
	 *
	 * @access public
	 * @param string $table
	 * @param array $data
	 * @param array $where
	 * @param array|string $format
	 * @return int|false
	 */
	public function update($table, $data = [], $where = [], $format = false)
	{
		return $this->db->update("{$this->prefix}{$this->basePrefix}{$table}", $data, $where, $format);
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
		$sql = "DELETE FROM {$this->prefix}{$this->basePrefix}{$table}";
		return $this->db->query($sql);
	}

	/**
	 * Delete Query
	 *
	 * @access public
	 * @param OrmQueryInterface $data
	 * @return int
	 */
	public function delete($table, $where = [], $format = null)
	{
		return $this->db->delete("{$this->prefix}{$this->basePrefix}{$table}", $where, $format);
	}
}
