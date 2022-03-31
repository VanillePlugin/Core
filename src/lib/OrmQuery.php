<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.7
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\OrmQueryInterface;
use VanillePlugin\inc\Arrayify;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\inc\Stringify;

final class OrmQuery implements OrmQueryInterface
{
	/**
	 * @access public
	 * @var array $query
	 */
	public $query = [];

	/**
	 * @param array $query
	 */
	public function __construct($query = [])
	{
		$this->query = $this->setDefault($query);
	}

	/**
	 * Set default query builder values.
	 * 
	 * @access private
	 * @param array $query
	 * @return array
	 */
	private function setDefault($query = [])
	{
		$query = Arrayify::merge([
			'table'    => '',
			'column'   => '*',
			'where'    => '',
			'orderby'  => '',
			'limit'    => '',
			'isSingle' => false,
			'isRow'    => false,
			'type'     => ARRAY_A
		], $query);

		// Set table
		if ( Stringify::contains($query['table'],',') ) {
			$tables = explode(',',$query['table']);
			foreach ($tables as $key => $table) {
				$table = trim($table);
				$tables[$key] = "_prefix_{$table}";
			}
			$query['table'] = implode(',',$tables);
			
		} else {
			$query['table'] = trim($query['table']);
			$query['table'] = "_prefix_{$query['table']}";
		}

		// Set where clause
		if ( !empty($query['where']) ) {
			if ( TypeCheck::isArray($query['where']) ) {
				$temp = [];
				foreach ($query['where'] as $field => $value) {
					$temp[] = "`{$field}` = {$value}";
				}
				$query['where'] = implode(' AND ',$temp);
			}
			$query['where'] = "WHERE {$query['where']}";
		}

		// Set orderby
		if ( !empty($query['orderby']) ) {
			$query['orderby'] = "ORDER BY {$query['orderby']}";
		}

		// Set limit
		if ( !empty($query['limit']) ) {
			$query['limit'] = "LIMIT {$query['limit']}";
		}

		return $query;
	}
}
