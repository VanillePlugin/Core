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

/**
 * Plugin table helper.
 * @uses Cache
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
		$key  = "model-{$this->table}-{$id}";
		$data = $this->getPluginCache($key, $status);

		if ( !$status ) {
			$where = ["{$this->key}" => (int)$id];
			$data  = $this->query(new OrmQuery([
				'result' => 'row',
				'where'  => $where
			]));
			$this->setPluginCache($key, $data);
		}

		return $data ?: [];
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
		$key  = "model-{$this->table}-all";
		$data = $this->getPluginCache($key, $status);
		
		if ( !$status ) {
			$data = $this->all();
			$this->setPluginCache($key, $data);
		}

		return $data ?: [];
	}
	
	/**
	 * Get cached item.
	 *
	 * @access public
	 * @param string $key
	 * @param bool $status
	 * @return array
	 */
	public static function getCached(string $key, ?bool &$status = null) : array
	{
		$sub  = basename(static::class);
		$key  = "model-{$sub}-{$key}";
		$data = (new Cache())->get($key, $status);
		return $data ?: [];
	}
	
	/**
	 * Instance database table.
	 *
	 * @access public
	 * @param string $name
	 * @param string $path
	 * @param mixed $args
	 * @return mixed
	 */
	public static function instance(string $name, $path = 'db', ...$args)
	{
		return (new Loader())->i($path, $name, ...$args);
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
