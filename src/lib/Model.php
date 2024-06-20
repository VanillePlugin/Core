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

use VanillePlugin\exc\ModelException;
use VanillePlugin\lib\{
	Orm, OrmQuery
};

/**
 * Helper class for database table model,
 * Using cache.
 */
class Model extends Orm
{
	Use \VanillePlugin\VanillePluginOption;

	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		if ( !$this->table ) {
	        throw new ModelException(
	            ModelException::undefinedTable()
	        );
		}
		$this->key = "{$this->table}Id";
		parent::__construct();
	}

	/**
	 * Get rendered table.
	 *
	 * @access public
	 * @return string
	 */
	public function render() : string
	{
		return $this->assign(
			self::fetch()
		);
	}

	/**
	 * Get item by Id.
	 *
	 * @access public
	 * @param mixed $id
	 * @return array
	 */
	public function get($id) : array
	{
		$key = "model-{$this->table}-{$id}";
		
		if ( !($data = $this->getPluginCache($key)) ) {

			$where = ["{$this->key}" => (int)$id];
			$data = $this->query(new OrmQuery([
				'result' => 'row',
				'where'  => $where
			]));
			$this->setPluginCache($key, $data);

		}

		return (array)$data;
	}

	/**
	 * Add item.
	 *
	 * @access public
	 * @param array $data
	 * @return bool
	 */
	public function add(array $data) : bool
	{
		return (bool)$this->create($data);
	}

	/**
	 * Update item.
	 *
	 * @access public
	 * @param array $data
	 * @return bool
	 */
	public function save(array $data) : bool
	{
		$where = ["{$this->key}" => (int)$data["{$this->key}"]];
		return (bool)$this->update($data, $where);
	}

	/**
	 * Update item status.
	 *
	 * @access public
	 * @param mixed $id
	 * @param int $status
	 * @return bool
	 */
	public function status($id, int $status = 0) : bool
	{
		$where = ["{$this->key}" => (int)$id];
		return (bool)$this->update([
			'status' => (int)$status
		], $where);
	}

	/**
	 * Duplicate item.
	 *
	 * @access public
	 * @param int $id
	 * @return bool
	 */
	public function duplicate($id) : bool
	{
		if ( ($data = $this->get($id)) ) {
			unset($data['date']);
			return $this->add($data);
		}
		return false;
	}

	/**
	 * Delete item.
	 *
	 * @access public
	 * @param int $id
	 * @return bool
	 */
	public function remove($id) : bool
	{
		$where = ["{$this->key}" => (int)$id];
		return (bool)$this->delete($where);
	}

	/**
	 * Get items.
	 *
	 * @access public
	 * @return array
	 */
	public function fetch() : array
	{
		$key = "model-{$this->table}-all";
		
		if ( !($data = $this->getPluginCache($key)) ) {
			$data = $this->all();
			$this->setPluginCache($key, $data);
		}

		return (array)$data;
	}

	/**
	 * Format datatable,
	 * '{"data":[["XXXX"],["XXXX"]]}'.
	 *
	 * @access protected
	 * @param array $data
	 * @return string
	 */
	protected function assign(array $data) : string
	{
		$wrapper = $this->map(function($item) {
			if ( $this->isType('array', $item)) {
				$item['action'] = '{actions}';
				return $this->arrayValues($item);
			}
		}, $data);

		$json = $this->formatJson(
			$this->formatArray($wrapper)
		);
		$prefix = '"data": ';

		return "{{$prefix}{$json}}";
	}
}
