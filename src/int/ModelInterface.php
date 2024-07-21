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

namespace VanillePlugin\int;

interface ModelInterface
{
	/**
	 * Get rendered table.
	 *
	 * @return string
	 */
	function render() : string;

	/**
	 * Get item by Id.
	 *
	 * @param mixed $id
	 * @return array
	 */
	function get($id) : array;

	/**
	 * Get item by Id and lang.
	 *
	 * @param mixed $id
	 * @return array
	 */
	function getWithLang($id) : array;

	/**
	 * Checl item exist.
	 *
	 * @param array $data
	 * @return bool
	 */
	function exist(array $data) : bool;

	/**
	 * Add item.
	 *
	 * @param array $data
	 * @return bool
	 */
	function add(array $data) : bool;

	/**
	 * Update item.
	 *
	 * @param array $data
	 * @return bool
	 */
	function save(array $data) : bool;

	/**
	 * Update item status.
	 *
	 * @param mixed $id
	 * @param int $status
	 * @return bool
	 */
	function status($id, int $status = 0) : bool;

	/**
	 * Duplicate item.
	 *
	 * @param int $id
	 * @return bool
	 */
	function duplicate($id) : bool;

	/**
	 * Delete item.
	 *
	 * @param int $id
	 * @return bool
	 */
	function remove($id) : bool;

	/**
	 * Get items.
	 *
	 * @return array
	 */
	function fetch() : array;

	/**
	 * Get items with langue.
	 *
	 * @return array
	 */
	function fetchWithLang() : array;
	
	/**
	 * Get cached item db instance.
	 *
	 * @param mixed $key
	 * @param bool $status
	 * @return array
	 */
	static function getCached($key, ?bool &$status = null) : array;
	
	/**
	 * Generate class based cache key.
	 *
	 * @param mixed $key
	 * @return string
	 */
	static function getCacheKey($key) : string;

	/**
	 * Format datatable:
	 * '{"data":[["XXXX"],["XXXX"]]}'.
	 *
	 * @param array $data
	 * @return string
	 */
	function assign(array $data) : string;

	/**
	 * Instance database table.
	 *
	 * @param string $name
	 * @param string $path
	 * @param mixed $args
	 * @return mixed
	 * @throws ModelException
	 */
	static function instance(string $name, $path = 'db', ...$args);
}
