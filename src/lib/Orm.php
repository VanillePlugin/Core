<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\Db;
use VanillePlugin\int\{
	OrmInterface, OrmQueryInterface
};

/**
 * Plugin CRUD manager.
 * @uses ORM style
 */
class Orm extends Db implements OrmInterface
{
	use \VanillePlugin\VanillePluginConfig;

	/**
	 * @access private
	 * @var bool $hasPrepare, Query prepare status
	 * @var bool $hasType, Data type status
	 */
	private $hasPrepare = true;
	private $hasType = true;

	/**
	 * @access protected
	 * @var string $table, Table name
	 * @var string $key, Primary key
	 */
	protected $table;
	protected $key;

	/**
	 * Init orm.
	 */
	public function __construct()
	{
		// Init db
		parent::__construct();

		// Display error
		if ( !$this->hasDebug() ) {
			$this->silent();
		}

		// Reset config
		$this->resetConfig();
	}

	/**
	 * @inheritdoc
	 */
	public function __set(string $field, $value)
	{
		$this->{$field} = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function __get(string $field)
	{
		return $this->{$field};
	}

	/**
	 * @inheritdoc
	 */
	public function noPrepare() : self
	{
		$this->hasPrepare = false;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function noType() : self
	{
		$this->hasType = false;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function setTable(string $table) : self
	{
		$this->table = $table;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function setKey(string $key) : self
	{
		$this->key = $key;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function create(array $data, ?string $table = null)
	{
		$data  = $this->sanitizeData($data);
		$table = $this->getTable($table);
		$types = $this->getTypes($data);

		return $this->db->insert($table, $data, $types);
	}

	/**
	 * @inheritdoc
	 */
	public function read($id = null, ?string $key = null, ?string $table = null) : array
	{
		$this->hasPrepare = true;

		$id    = $this->getId($id);
		$key   = $this->getKey($key);
		$table = $this->getTable($table);

		$sql = "SELECT * FROM `{$table}` WHERE `{$this->key}` = {{id}} LIMIT 1;";
		return $this->getRow($sql, ['id' => $id]);
	}

	/**
	 * @inheritdoc
	 */
	public function update(array $data, array $where = [], ?string $table = null) : int
	{
		$data  = $this->sanitizeData($data);
		$where = $this->sanitizeData($where);
		$table = $this->getTable($table);
		$key   = $this->getKey();

		if ( isset($data[$key]) ) {
			unset($data[$key]);
		}

		$types = [
			'data'  => $this->getTypes($data),
			'where' => $this->getTypes($where)
		];

		return (int)$this->db->update($table, $data, $where, $types['data'], $types['where']);
	}

	/**
	 * @inheritdoc
	 */
	public function delete(array $where = [], ?string $table = null) : int
	{
		$table = $this->getTable($table);
		$types = $this->getTypes($where);

		if ( empty($where) ) {
			$key = $this->getKey();
			$where[$key] = $this->key;
		}

		return (int)$this->db->delete($table, $where, $types);
	}

	/**
	 * @inheritdoc
	 */
	public function execute(string $sql, array $data = [])
	{
		$sql = $this->prepareQuery($sql, $data);
		return $this->db->query($sql);
	}
	
	/**
	 * @inheritdoc
	 */
	public function getResult(string $sql, array $data = []) : array
	{
		$sql = $this->prepareQuery($sql, $data);
		return $this->db->get_results($sql, 'ARRAY_A');
	}

	/**
	 * @inheritdoc
	 */
	public function getField(string $sql, array $data = [], int $x = 0, int $y = 0)
	{
		$sql = $this->prepareQuery($sql, $data);
		return $this->db->get_var($sql, $x, $y);
	}

	/**
	 * @inheritdoc
	 */
	public function getRow(string $sql, array $data = [], $y = 0) : array
	{
		$sql = $this->prepareQuery($sql, $data);
		return (array)$this->db->get_row($sql, 'ARRAY_A', $y);
	}

	/**
	 * @inheritdoc
	 */
	public function getColumn(string $sql, array $data = [], int $x = 0) : array
	{
		$sql = $this->prepareQuery($sql, $data);
		return (array)$this->db->get_col($sql, $x);
	}

	/**
	 * Get min value.
	 *
	 * @access public
	 * @param string $column
	 * @param string $table
	 * @return mixed
	 */
	public function min(string $column, ?string $table = null)
	{
		$sql = "SELECT MIN(`{$column}`) FROM `{$this->getTable($table)}`;";
		return $this->getField($sql);
	}

	/**
	 * @inheritdoc
	 */
	public function max(string $column, ?string $table = null)
	{
		$sql = "SELECT MAX(`{$column}`) FROM `{$this->getTable($table)}`;";
		return $this->getField($sql);
	}

	/**
	 * @inheritdoc
	 */
	public function avg(string $column, ?string $table = null)
	{
		$sql = "SELECT AVG(`{$column}`) FROM `{$this->getTable($table)}`;";
		return $this->getField($sql);
	}

	/**
	 * @inheritdoc
	 */
	public function sum(string $column, ?string $table = null)
	{
		$sql = "SELECT SUM(`{$column}`) FROM `{$this->getTable($table)}`;";
		return $this->getField($sql);
	}
	
	/**
	 * @inheritdoc
	 */
	public function all(?string $table = null) : array
	{
		$sql = "SELECT * FROM `{$this->getTable($table)}`;";
		return $this->getResult($sql);
	}

	/**
	 * @inheritdoc
	 */
	public function count(?string $table = null) : int
	{
		$sql = "SELECT COUNT(*) FROM `{$this->getTable($table)}`;";
		return (int)$this->getField($sql);
	}

	/**
	 * @inheritdoc
	 */
    public function insertId() : int
    {
        return (int)$this->db->insert_id;
    }

	/**
	 * @inheritdoc
	 */
	public function clear(?string $table = null, bool $reset = true) : int
	{
		$sql = "DELETE FROM `{$this->getTable($table)}`;";
		$count = $this->execute($sql);
		if ( $reset ) $this->resetId($table);
		return $count;
	}

	/**
	 * @inheritdoc
	 */
	public function resetId(?string $table = null) : bool
	{
		$sql = "ALTER TABLE `{$this->getTable($table)}` AUTO_INCREMENT = 1;";
		return (bool)$this->execute($sql);
	}

	/**
	 * @inheritdoc
	 */
	public function hasTable(bool $wildcard = false, ?string $table = null) : bool
	{
		if ( !$wildcard ) {
			$table = $this->getTable($table);

		} else {
			$table = "%{$this->prefix}$table%";
		}
		
		$sql = "SHOW TABLES LIKE '{$table}';";
		return (bool)$this->execute($sql);
	}

	/**
	 * @inheritdoc
	 */
	public function hasColumn(string $column, ?string $table = null) : bool
	{
		$table = $this->getTable($table);
		$sql = "SHOW COLUMNS FROM `{$table}` LIKE '{$column}';";
		return (bool)$this->execute($sql);
	}

	/**
	 * @inheritdoc
	 */
	public function columns(?string $table = null) : array
	{
		$sql  = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS ";
		$sql .= "WHERE TABLE_NAME = '{$this->getTable($table)}' ";
		$sql .= "ORDER BY ORDINAL_POSITION;";
		$columns = $this->getColumn($sql);
		return $this->uniqueArray($columns);
	}
	
	/**
	 * @inheritdoc
	 */
	public function tables() : array
	{
		return $this->getColumn('SHOW TABLES;');
	}

	/**
	 * @inheritdoc
	 */
	public function query(OrmQueryInterface $builder)
	{
		$builder->setTable($this->table);
		$prefix = "{$this->prefix}{$this->getPrefix()}";
		if ( ($query = $builder->getQuery($prefix)) ) {

			switch ( $builder->result ) {
				case 'any':
					return $this->getResult($query);
					break;
				
				case 'field':
					return $builder->format(
						$this->getField($query)
					);
					break;
				
				case 'row':
					return $this->getRow($query);
					break;
				
				case 'column':
					return $this->getColumn($query);
					break;
				
				default:
					return $this->execute($query);
					break;
			}

		}
	}

	/**
	 * Get plugin table name.
	 * 
	 * @access protected
	 * @param string $table
	 * @return string
	 */
	protected function getTable(?string $table = null) : string
	{
		$temp = (string)$this->table;
		if ( $table ) $temp = $table;
		return "{$this->prefix}{$this->applyPrefix($temp)}";
	}
	
	/**
	 * Get table key.
	 * 
	 * @access protected
	 * @param string $key
	 * @return string
	 */
	protected function getKey(?string $key = null) : string
	{
		$temp = (string)$this->key;
		if ( $key ) $temp = $key;
		return $temp;
	}
	
	/**
	 * Get table Id.
	 * 
	 * @access protected
	 * @param mixed $id
	 * @return mixed
	 */
	protected function getId($id = null)
	{
		$temp = $this->{$this->key};
		if ( $id ) $temp = $id;
		return $temp;
	}

	/**
	 * Prepare query.
	 *
	 * @access protected
	 * @param string $sql
	 * @param array $data
	 * @return string
	 */
	protected function prepareQuery(string $sql, array $data) : string
	{
		if ( $this->hasPrepare && $data ) {
			foreach ($data as $key => $value) {
				if ( $this->isType('int', $value) ) {
					$sql = $this->replaceString("{{{$key}}}", '%d', $sql);
	
				} elseif ( $this->isType('float', $value) ) {
					$sql = $this->replaceString("{{{$key}}}", '%f', $sql);
	
				} else {
					$sql = $this->replaceString("{{{$key}}}", '%s', $sql);
				}
			}
	
			$prepare = $this->db->prepare($sql, $data);
			if ( $prepare ) {
				$sql = $prepare;
			}
		}
		return $this->formatQuery($sql);
	}

	/**
	 * Get data types.
	 *
	 * @access protected
	 * @param array $data
	 * @return mixed
	 */
	protected function getTypes(array $data)
	{
		if ( $this->hasType && $data ) {
			$types = [];
			foreach ($data as $key => $value) {
				if ( $this->isType('int', $value) ) {
					$types[] = '%d';
	
				} elseif ( $this->isType('float', $value) ) {
					$types[] = '%f';
					
				} else {
					$types[] = '%s';
				}
			}
			return $types;
		}
		return null;
	}

	/**
	 * Format query.
	 *
	 * @access protected
	 * @param string $sql
	 * @return string
	 */
	protected function formatQuery(string $sql) : string
	{
		$sql = $this->formatSpace($sql);
		if ( substr($sql, -1) !== ';' ) {
			$sql .= ';';
		}
		return $sql;
	}

	/**
	 * Sanitize data.
	 *
	 * @access protected
	 * @param array $data
	 * @return array
	 */
	protected function sanitizeData(array $data) : array
	{
		foreach ($data as $key => $value) {
			if ( !$this->isType('string', $key) ) {
				unset($data[$key]);
			}
		}
		return $data;
	}

	/**
	 * Hide error.
	 *
	 * @access protected
	 * @return void
	 */
	protected function silent()
	{
		$this->db->hide_errors();
	}
}
