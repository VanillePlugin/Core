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
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\int\ModelInterface;

/**
 * Plugin table helper.
 * @uses Cache
 */
class Model extends Orm implements ModelInterface
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
	 * @inheritdoc
	 */
	public function render() : string
	{
		return $this->assign(
			self::fetch()
		);
	}

	/**
	 * @inheritdoc
	 */
	public function get($id) : array
	{
		$key  = self::getCacheKey($id);
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
	 * @inheritdoc
	 */
	public function getWithLang($id) : array
	{
		$key  = self::getCacheKey($id);
		$data = $this->getPluginCache($key, $status);

		if ( !$status ) {
			$where = [
				"{$this->key}" => (int)$id,
				'lang' => $this->getLang()
			];
			$data  = $this->query(new OrmQuery([
				'result' => 'row',
				'where'  => $where
			]));
			$this->setPluginCache($key, $data);
		}

		return $data ?: [];
	}

	/**
	 * @inheritdoc
	 */
	public function exist(array $data) : bool
	{
		return (bool)$this->query(new OrmQuery([
			'result' => 'count',
			'where'  => $data
		]));
	}

	/**
	 * @inheritdoc
	 */
	public function add(array $data) : bool
	{
		$data = $this->format($data);
		unset($data['date']);
		if ( !$this->exist($data) ) {
			return (bool)$this->create($data);
		}
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function save(array $data) : bool
	{
		$where = ["{$this->key}" => (int)$data["{$this->key}"]];
		$data = $this->format($data);
		return (bool)$this->update($data, $where);
	}

	/**
	 * @inheritdoc
	 */
	public function status($id, int $status = 0) : bool
	{
		$where = ["{$this->key}" => (int)$id];
		return (bool)$this->update([
			'status' => (int)$status
		], $where);
	}

	/**
	 * @inheritdoc
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
	 * @inheritdoc
	 */
	public function remove($id) : bool
	{
		$where = ["{$this->key}" => (int)$id];
		return (bool)$this->delete($where);
	}

	/**
	 * @inheritdoc
	 */
	public function fetch() : array
	{
		$key  = self::getCacheKey('all');
		$data = $this->getPluginCache($key, $status);
		
		if ( !$status ) {
			$data = $this->all();
			$this->setPluginCache($key, $data);
		}

		return $data ?: [];
	}

	/**
	 * @inheritdoc
	 */
	public function fetchWithLang() : array
	{
		$key  = self::getCacheKey('all');
		$data = $this->getPluginCache($key, $status);
		
		if ( !$status ) {
			$data = $this->query(new OrmQuery([
				'where' => ['lang' => $this->getLang()]
			]));
			$this->setPluginCache($key, $data);
		}
		
		return (array)$data;
	}

	/**
	 * @inheritdoc
	 */
	public function assign(array $data) : string
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

	/**
	 * @inheritdoc
	 */
	public static function getCached($key, ?bool &$status = null) : array
	{
		$key  = self::getCacheKey($key);
		$data = (new Cache())->get($key, $status);
		return $data ?: [];
	}

	/**
	 * @inheritdoc
	 */
	public static function setCached($key, $data) : bool
	{
		$key = self::getCacheKey($key);
		return (new Cache())->set($key, $data);
	}
	
	/**
	 * @inheritdoc
	 */
	public static function getCacheKey($key) : string
	{
		$obj = basename(self::class);
		$sub = basename(static::class);
		return "{$obj}-{$sub}-{$key}";
	}
	
	/**
	 * @inheritdoc
	 */
	public static function instance(string $name, $path = 'db', ...$args)
	{
		$class = (new Loader())->i($path, $name, ...$args);
		if ( !TypeCheck::hasInterface($class, 'ModelInterface') ) {
			throw new ModelException(
				ModelException::invalidInstance()
			);
		}
		return $class;
	}
	
	/**
	 * Format entities.
	 *
	 * @param array $data
	 * @return array
	 */
	protected function format(array $data) : array
	{
		return $data;
	}
}
