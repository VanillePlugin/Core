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

interface OrmQueryInterface
{
	/**
	 * Init query builder using array.
	 * 
	 * @param array $args
	 */
	function __construct(array $args = []);

	/**
	 * Set property.
	 * 
	 * @param string $property
	 */
	function __set(string $property, $value);

	/**
	 * Get property.
	 * 
	 * @param string $property
	 * @param mixed
	 */
	function __get(string $property);

	/**
	 * Get complete query by type.
	 * 
	 * @param string $prefix
	 * @return mixed
	 */
	function getQuery(?string $prefix = null);

	/**
	 * Format query single result value.
	 * 
	 * @param mixed $value
	 * @return mixed
	 */
	function format($value = null);

	/**
	 * Set table.
	 * 
	 * @param string $table
	 * @return void
	 */
	function setTable(?string $table = null);
}
