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

use VanillePlugin\int\OrmQueryInterface;

/**
 * Plugin ORM query builder (Read-only).
 */
final class OrmQuery implements OrmQueryInterface
{
	use \VanillePlugin\tr\TraitFormattable;

	/**
	 * @access private
	 * @var string ALIAS, Column, table alias
	 * @var string ALIASED, Column aliasd
	 * @var string EXTEND, Table alias
	 * @var string ALIASED, Table alias
	 * @var string WRAPPER, Name wrapper
	 */
	private const ALIAS   = '=>';
	private const ALIASED = ' AS ';
	private const WRAPPER = '`';

	/**
	 * @access private
	 * @var array $columnAlias
	 * @var array $tableAlias
	 */
	private $columnAlias = [];
	private $tableAlias = [];

	/**
	 * @access private
	 * @var mixed $table
	 * @var string $type
	 * @var string $prefix
	 * @var string $orderby
	 * @var string $groupby
	 * @var int $format
	 * @var mixed $column
	 * @var mixed $distinct
	 * @var mixed $where
	 * @var mixed $reverse
	 * @var mixed $conjunct
	 * @var mixed $before
	 * @var mixed $after
	 * @var mixed $limit
	 * @var mixed $offset
	 */
	private $table;
	private $type;
	private $prefix;
	private $orderby;
	private $groupby;
	private $format;
	private $column;
	private $distinct;
	private $where;
	private $reverse;
	private $conjunct;
	private $before;
	private $after;
	private $limit;
	private $offset;

	/**
	 * @access public
	 * @var string $result
	 */
	public $result;

	/**
	 * @inheritdoc
	 */
	public function __construct(array $args = [])
	{
		// Init properties
		$args = $this->mergeArray([
			'table'     => '',       // Array | String
			'type'      => 'select', // String (select, count)
			'result'    => 'any',    // String (any, none, field, row, column)
			'orderby'   => '',       // Array | String
			'groupby'   => '',       // Array | String
			'format'    => '',       // String
			'prefix'    => '',       // String
			'column'    => '*',      // Array | String
			'distinct'  => false,    // Array | String | Bool
			'where'     => [],       // Array | String
			'reverse'   => false,    // Array | String | Bool
			'conjunct'  => [],       // Array | String
			'before'    => '',       // Array | String
			'after'     => '',       // Array | String
			'limit'     => false,    // Int   | Bool
			'offset'    => false     // Int   | Bool
		], $args);

		// Define properties
		foreach ($args as $property => $value) {
			$this->$property = $value;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function __set(string $property, $value)
	{
		$this->{$property} = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function __get(string $property)
	{
		return $this->{$property};
	}

	/**
	 * @inheritdoc
	 */
	public function getQuery(?string $prefix = null)
	{
		$this->setPrefix($prefix);

		$sql  = false;
		$type = (string)$this->type;

		if ( $this->lowercase($type) == 'select' ) {
			$sql  = "{$this->getSelectString()} ";
			$sql .= "{$this->getWhereString()} ";
			$sql .= "{$this->getGroupbyString()} ";
			$sql .= "{$this->getOrderbyString()} ";
			$sql .= "{$this->getLimitString()} ";
			$sql .= "{$this->getOffsetString()} ";

		} elseif ( $this->lowercase($type) == 'count' ) {

			$this->result = 'field';
			$this->format = 'int';

			$sql  = "{$this->getSelectCountString()} ";
			$sql .= "{$this->getWhereString()} ";
		}
		
		$sql = trim($sql);
		return "{$sql};";
	}

	/**
	 * @inheritdoc
	 */
	public function format($value = null)
	{
		$format = $this->inArray($this->format, [
			'int', 'float', 'bool'
		]);
		if ( $format ) {
			if ( $this->format == 'int' ) {
				return intval($value);
			}
			if ( $this->format == 'float' ) {
				return floatval($value);
			}
			if ( $this->format == 'bool' ) {
				return (bool)$value;
			}
		}
		return $value;
	}

	/**
	 * @inheritdoc
	 */
	public function setTable(?string $table = null)
	{
		if ( $table && !$this->table ) {
			$this->table = $table;
		}
	}

	/**
	 * Get select query string.
	 * 
	 * @access private
	 * @return string
	 */
	private function getSelectString() : string
	{
		$sql  = 'SELECT ';
		$sql .= "{$this->getSelectColumnString()} ";
		$sql .= "FROM {$this->getTableString()}";
		return $sql;
	}

	/**
	 * Get select count query string.
	 * 
	 * @access private
	 * @return string
	 */
	private function getSelectCountString() : string
	{
		$sql  = "SELECT COUNT({$this->getSelectColumnString()}) ";
		$sql .= "FROM {$this->getTableString()}";
		return $sql;
	}

	/**
	 * Get where clause string.
	 * 
	 * @access private
	 * @return string
	 */
	private function getWhereString() : string
	{
		$sql = '';
		if ( $this->where ) {
			
			if ( $this->isType('string', $this->where) ) {
				return "WHERE {$this->where}";
			}

			if ( $this->isType('array', $this->where) ) {

				$keys = $this->arrayKeys($this->where);
				$last = end($keys);
				$sql .= 'WHERE ';
				foreach ($this->where as $key => $value) {
					
					// Set type
					if ( $this->isType('string', $value) ) {
						if ( !$this->hasString($value, self::WRAPPER) ) {
							$value = "'{$value}'";
						}
	
					} elseif ( $this->isType('true', $value) ) {
						$value = 'TRUE';
	
					} elseif ( $this->isType('false', $value) ) {
						$value = 'FALSE';
	
					} elseif ( $this->isType('null', $value) ) {
						$value = 'NULL';
					}

					// Set before
					$sql .= $this->getBefore($key);

					// Set column
					$sql .= "{$this->setNameWrapper($key)} ";

					// Set comparator
					$sql .= "{$this->getComparator($key, $value)} ";

					// Set value
					$sql .= "{$value} ";

					// Set conjunction
					if ( $last !== $key ) {
						$sql .= "{$this->getConjunction($key)} ";

					} else {
						$sql = rtrim($sql, ' ');
					}

					// Set after
					$sql .= "{$this->getAfter($key)}";
				}
			}
		}

		return $sql;
	}

	/**
	 * Get ORDER BY clause string.
	 *
	 * @access private
	 * @return string
	 */
	private function getOrderbyString() : string
	{
		$sql = '';
		$orderby = $this->parseOrderby();
		if ( !empty($orderby) ) {
			$sql .= 'ORDER BY ' . implode(', ', $orderby);
		}
		return $sql;
	}

	/**
	 * Get GROUP BY clause string.
	 *
	 * @access private
	 * @return string
	 */
	private function getGroupbyString() : string
	{
		$sql = '';
		$groupby = $this->parseGroupby();
		if ( !empty($groupby) ) {
			$sql .= 'GROUP BY ' . implode(', ', $groupby);
		}
		return $sql;
	}

	/**
	 * Get LIMIT query string.
	 *
	 * @access private
	 * @return string
	 */
	private function getLimitString() : string
	{
		$sql = '';
		if ( $this->isType('int', $this->limit) ) {
			$sql .= "LIMIT {$this->limit}";
		}
		return $sql;
	}

	/**
	 * Get offset query string.
	 *
	 * @access private
	 * @return string
	 */
	private function getOffsetString() : string
	{
		$sql = '';
		if ( $this->isType('int', $this->offset) ) {
			$sql .= "OFFSET {$this->offset}";
		}
		return $sql;
	}

	/**
	 * Get table(s) string including alias.
	 * 
	 * @access private
	 * @return string
	 */
	private function getTableString() : string
	{
		// Init
		$sql   = '';
		$table = $this->parseTable();

		foreach ($table as $key => $tab) {
			$tab = "{$this->getPrefix()}$tab";
			$sql .= $this->setTableWrapper($tab);
			if ( ($key+1) < count($table) ) {
				$sql .= ', ';
			}
		}

		return $sql;
	}

	/**
	 * Get select column(s) string including distinct.
	 * 
	 * @access private
	 * @return string
	 */
	private function getSelectColumnString() : string
	{
		// Init
		$column   = $this->parseColumn();
		$distinct = $this->parseDistinct();

		// Set distinct all
		if ( $distinct === true ) {
			$sql = 'DISTINCT ';
			foreach ($column as $key => $col) {
				$sql .= $this->setColumnWrapper($col);
				if ( ($key+1) < count($column) ) {
					$sql .= ', ';

				} else {
					$sql .= ' ';
				}
			}
			return $sql;
		}
		
		// Set distinct mixed
		if ( $this->isType('array', $distinct) && !empty($distinct) ) {

			// Set distinct custom
			if ( count($distinct) == 1 && $distinct[0] !== '*' ) {
				return "DISTINCT {$distinct[0]}";
			}

			// Set distinct any
			if ( count($distinct) == 1 && $distinct[0] == '*' ) {
				return 'DISTINCT(*)';
			}

			$distincted = [];
			foreach ($column as $key => $col) {
				if ( $this->inArray($col, $distinct) ) {
					$distincted[] = $col;
					unset($column[$key]);
				}
			}

			if ( $distincted ) {

				$sql = 'DISTINCT(';
				foreach ($distincted as $key => $col) {
					$sql .= $this->setColumnWrapper($col);
					if ( ($key+1) < count($distincted) ) {
						$sql .= ', ';
	
					} else {
						$sql .= ')';
					}
				}

				if ( $column ) {
					$sql .= ', ';
					foreach ($column as $key => $col) {
						$sql .= $this->setColumnWrapper($col);
						if ( $key < count($column) ) {
							$sql .= ', ';
						}
					}
				}
				
				return $sql;
			}
		}

		// Set distinct any
		if ( count($column) == 1 && $column[0] == '*' ) {

			if ( $distinct === true ) {
				return 'DISTINCT(*)';
			}

			// Set any
			return '*';
		}

		// Set column
		$sql = '';
		foreach ($column as $key => $col) {
			$sql .= $this->setColumnWrapper($col);
			if ( ($key+1) < count($column) ) {
				$sql .= ', ';
			}
		}

		return $sql;
	}

	/**
	 * Get query comparator by value type,
	 * Fix result conflits.
	 * 
	 * @access private
	 * @param string $key
	 * @param mixed $value
	 * @return string
	 */
	private function getComparator(string $key, $value) : string
	{
		$comparator = 'LIKE';

		// Set comparator
		if ( $this->isType('int', $value) 
		  || $this->isType('float', $value)  ) {
			$comparator = '=';

		} elseif ( $this->isType('true', $value) 
		        || $this->isType('false', $value) 
				|| $this->isType('null', $value) ) {
			$comparator = 'is';
		}

		// Reverse comparator
		if ( $this->isType('array', $this->reverse) ) {
			if ( $this->inArray($key, $this->reverse) ) {
				$comparator = "NOT {$comparator}";
			}

		} elseif ( $this->isType('string', $this->reverse) ) {
			if ( $this->reverse == $key ) {
				$comparator = "NOT {$comparator}";
			}

		} elseif ( $this->isType('bool', $this->reverse) ) {
			if ( $this->reverse == true ) {
				$comparator = "NOT {$comparator}";
			}
		}

		return $comparator;
	}

	/**
	 * Get query conjunction.
	 * 
	 * @access private
	 * @param string $key
	 * @return string
	 */
	private function getConjunction(string $key) : string
	{
		$conjunction = 'AND';

		if ( $this->isType('array', $this->conjunct) ) {
			if ( isset($this->conjunct[$key]) ) {
				$conjunction = $this->conjunct[$key];
			}

		} elseif ( $this->isType('string', $this->conjunct) ) {
			$conjunction = $this->conjunct;
		}

		return $this->uppercase($conjunction);
	}

	/**
	 * Get where clause before.
	 * 
	 * @access private
	 * @param string $key
	 * @return string
	 */
	private function getBefore(string $key) : string
	{
		$before = '';
		if ( $this->isType('array', $this->before) ) {
			if ( $this->inArray($key, $this->before) ) {
				$before = '(';
			}

		} elseif ( $this->isType('string', $this->before) ) {
			if ( $this->before == $key ) {
				$before = '(';
			}
		}
		return $before;
	}

	/**
	 * Get where clause after.
	 * 
	 * @access private
	 * @param string $key
	 * @return string
	 */
	private function getAfter(string $key) : string
	{
		$after = '';
		if ( $this->isType('array', $this->after) ) {
			if ( $this->inArray($key, $this->after) ) {
				$after = ')';
			}

		} elseif ( $this->isType('string', $this->after) ) {
			if ( $this->after == $key ) {
				$after = ')';
			}
		}
		return $after;
	}

	/**
	 * Get table prefix.
	 * 
	 * @access private
	 * @return string
	 */
	private function getPrefix() : string
	{
		$prefix = (string)$this->prefix;
		$prefix = $this->stripSpace($prefix);
		if ( substr($prefix, -1) !== '_' ) {
			$prefix .= '_';
		}
		return $prefix;
	}

	/**
	 * Set table prefix.
	 * 
	 * @access private
	 * @param string $prefix
	 * @return void
	 */
	private function setPrefix(?string $prefix = null)
	{
		if ( $prefix && !$this->prefix ) {
			$this->prefix = $prefix;
		}
	}

	/**
	 * Set name wrapper [`],
	 * Escape reserved names.
	 * 
	 * @access private
	 * @param string $name
	 * @return string
	 */
	private function setNameWrapper(string $name) : string
	{
		if ( $this->hasString($name, 'count(') ) {
			$name = $this->replaceString('count(', 'COUNT(`', $name);
			$name = $this->replaceString(')', '`)', $name);
		}
		if ( !$this->hasString($name, self::WRAPPER) ) {
			$name = "`{$name}`";
		}
		return $name;
	}

	/**
	 * Set column wrapper [`] including alias.
	 * 
	 * @access private
	 * @param string $column
	 * @return string
	 */
	private function setColumnWrapper(string $column) : string
	{
		// Init
		$wrapper = $column;

		// Set wrapper
		$wrapper = $this->setNameWrapper($wrapper);

		// Apply alias
		if ( !$this->hasString($wrapper, self::ALIASED) ) {
			if ( isset($this->columnAlias[$column]) ) {
				$wrapper .= " AS `{$this->columnAlias[$column]}`";
			}
		}
		
		return $wrapper;
	}

	/**
	 * Set table wrapper [`] including alias.
	 * 
	 * @access private
	 * @param string $table
	 * @return string
	 */
	private function setTableWrapper(string $table) : string
	{
		// Init
		$wrapper = $table;

		// Set wrapper
		if ( !$this->hasString($wrapper, self::WRAPPER) ) {
			$wrapper = "`{$wrapper}`";
		}
		
		// Apply alias
		if ( !$this->hasString($wrapper, self::ALIAS) ) {
			if ( isset($this->tableAlias[$table]) ) {
				$wrapper .= " {$this->tableAlias[$table]}";
			}
		}

		return $wrapper;
	}

	/**
	 * Parse distinct(s) statement as array.
	 * 
	 * @access private
	 * @return mixed
	 */
	private function parseDistinct()
	{
		// Init
		$distinct = $this->distinct;

		// Convert string
		if ( $this->isType('string', $distinct) ) {
			$distinct = $this->stripSpace($distinct);
			if ( $this->hasString($distinct, ',') ) {
				return explode(',', $distinct);

			} else {
				return [$distinct];
			}
		}

		return $distinct;
	}

	/**
	 * Parse column(s) name as array.
	 * 
	 * @access private
	 * @return array
	 */
	private function parseColumn() : array
	{
		// Init
		$column = $this->column;

		// Convert string
		if ( $this->isType('string', $column) ) {
			$column = $this->stripSpace($column);
			if ( $column == '*' || empty($column) ) {
				$column = ['*'];

			} elseif ( $this->hasString($column, ',') ) {
				$column = explode(',', $column);

			} else {
				$column = [$column];
			}
		}

		// Convert any
		if ( !$this->isType('array', $column) || empty($column) ) {
			$column = ['*'];
		}

		// Set alias
		foreach ($column as $key => $col) {
			if ( $this->hasString($col, self::ALIAS) ) {
				$col = $this->stripSpace($col);
				$alias = explode(self::ALIAS, $col);
				if ( count($alias) == 2 && !empty($alias[0]) && !empty($alias[1]) ) {
					$this->columnAlias[$alias[0]] = $alias[1];
					$column[$key] = $alias[0];
				}
			}
		}

		return $column;
	}

	/**
	 * Parse table(s) name as array.
	 * 
	 * @access private
	 * @return array
	 */
	private function parseTable() : array
	{
		// Init
		$table = $this->table;

		// Convert string
		if ( $this->isType('string', $table) ) {
			$table = $this->stripSpace($table);
			if ( $this->hasString($table, ',') ) {
				$table = explode(',', $table);

			} else {
				$table = [$table];
			}
		}
		
		// Convert any
		if ( !$this->isType('array', $table) ) {
			$table = [];
		}

		// Set alias
		foreach ($table as $key => $tab) {
			if ( $this->hasString($tab, self::ALIAS) ) {
				$tab = $this->stripSpace($tab);
				$alias = explode(self::ALIAS, $tab);
				if ( count($alias) == 2 && !empty($alias[0]) && !empty($alias[1]) ) {
					$this->tableAlias["{$this->getPrefix()}{$alias[0]}"] = $alias[1];
					$table[$key] = $alias[0];
				}
			}
		}

		return $table;
	}

	/**
	 * Parse ORDER BY clause as array.
	 * 
	 * @access private
	 * @return array
	 */
	private function parseOrderby() : array
	{
		// Init
		$orderby = $this->orderby;

		if ( empty($orderby) ) {
			return [];
		}

		// Convert string
		if ( $this->isType('string', $orderby) ) {
			$orderby = $this->stripSpace($orderby);
			if ( $this->hasString($orderby, ',') ) {
				$orderby = explode(',', $orderby);

			} else {
				$orderby = [$orderby];
			}
		}

		// Convert any
		if ( !$this->isType('array', $orderby) ) {
			$orderby = [];
		}

		// Set sort
		$sorted = [];
		foreach ($orderby as $key => $order) {
			if ( $this->isType('string', $key) ) {
				if ( !$order ) $order = 'ASC';
				$order = $this->uppercase($order);
				$key = "{$key} {$order}";
				$sorted[] = $key;

			} else {
				$sorted[] = $order;
			}
		}

		return $sorted;
	}

	/**
	 * Parse GROUP BY clause as array.
	 * 
	 * @access private
	 * @return array
	 */
	private function parseGroupby() : array
	{
		// Init
		$groupby = $this->groupby;

		if ( empty($groupby) ) {
			return [];
		}

		// Convert string
		if ( $this->isType('string', $groupby) ) {
			$groupby = $this->stripSpace($groupby);
			if ( $this->hasString($groupby, ',') ) {
				$groupby = explode(',', $groupby);

			} else {
				$groupby = [$groupby];
			}
		}

		// Convert any
		if ( !$this->isType('array', $groupby) ) {
			$groupby = [];
		}

		return $groupby;
	}
}
