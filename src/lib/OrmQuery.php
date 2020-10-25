<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.8
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\OrmQueryInterface;

class OrmQuery implements OrmQueryInterface
{
	/**
	 * @access public
	 * @var array $query
	 */
	public $query = [];

	/**
	 * @param array $query
	 * @return void
	 */
	public function __construct($query = [])
	{
		$query = array_merge([

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

		$query['where'] = !empty($query['where'])
		? "WHERE {$query['where']}" : '';

		$query['limit'] = !empty($query['limit'])
		? "LIMIT {$query['limit']}" : '';

		$query['orderby'] = !empty($query['orderby'])
		? "ORDER BY {$query['orderby']}" : '';

		$this->query = $query;
		return $this->query;
	}
}
