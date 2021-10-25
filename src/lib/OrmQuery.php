<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.3
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
	 * @access public
	 * @param array $query
	 * @return varray
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
			'format'   => null,
			'type'     => ARRAY_A
		], $query);

		if ( TypeCheck::isArray($query['where']) ) {
			$temp = [];
			foreach ($query['where'] as $property => $value) {
				$temp[] = "`{$property}` = {$value}";
			}
			$query['where'] = implode(' AND ',$temp);
		}

		$query['where'] = !empty($query['where'])
		? "WHERE {$query['where']}" : '';

		$query['limit'] = !empty($query['limit'])
		? "LIMIT {$query['limit']}" : '';

		$query['orderby'] = !empty($query['orderby'])
		? "ORDER BY {$query['orderby']}" : '';

		return $query;
	}
}
