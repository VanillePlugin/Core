<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.4
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

final class Arrayify
{
	/**
	 * @access public
	 * @param mixed $needle
	 * @param array $haystack
	 * @return bool
	 */
	public static function inArray($needle, $haystack)
	{
		return in_array($needle,(array)$haystack,true);
	}

	/**
	 * @access public
	 * @param array $array
	 * @param array $arrays
	 * @return array
	 */
	public static function merge($array, $arrays)
	{
		return array_merge($array,(array)$arrays);
	}

	/**
	 * @access public
	 * @param array $array
	 * @param mixed $values
	 * @return int
	 */
	public static function push(&$array,$values)
	{
		return array_push($array,$values);
	}

	/**
	 * @access public
	 * @param array $keys
	 * @param array $values
	 * @return array
	 */
	public static function combine($keys, $values)
	{
		return array_combine((array)$keys,(array)$values);
	}

	/**
	 * @access public
	 * @param callable $callback
	 * @param array $array
	 * @param array $arrays
	 * @return array
	 */
	public static function map($callback, $array, $arrays = null)
	{
		if ( !TypeCheck::isNull($arrays) ) {
			return array_map($callback,(array)$array,$arrays);
		}
		return array_map($callback,(array)$array);
	}

	/**
	 * @access public
	 * @param array $array
	 * @return mixed
	 */
	public static function shift(&$array)
	{
		return array_shift($array);
	}

	/**
	 * @access public
	 * @param array $array
	 * @param array $arrays
	 * @return mixed
	 */
	public static function diff($array, $arrays)
	{
		return array_diff($array,$arrays);
	}

	/**
	 * @access public
	 * @param string|int $key
	 * @param array $array
	 * @return bool
	 */
	public static function hasKey($key, $array)
	{
		return array_key_exists($key,$array);
	}

	/**
	 * @access public
	 * @param array $array
	 * @return array
	 */
	public static function keys($array)
	{
		return array_keys($array);
	}

	/**
	 * @access public
	 * @param array $array
	 * @return array
	 */
	public static function values($array)
	{
		return array_values($array);
	}

	/**
	 * @access public
	 * @param array $array
	 * @param int $flags
	 * @return array
	 */
	public static function unique($array, $flags = SORT_STRING)
	{
		return array_unique($array,$flags);
	}

	/**
	 * @access public
	 * @param array $array
	 * @param int $num
	 * @return mixed
	 */
	public static function rand($array, $num = 1)
	{
		return array_rand($array,$num);
	}

	/**
	 * @access public
	 * @param array $array
	 * @param int $offset
	 * @param int $length
	 * @param bool $preserve
	 * @return array
	 */
	public static function slice($array, $offset, $length = null, $preserve = false)
	{
		return array_slice($array,$offset,$length,$preserve);
	}

	/**
	 * @access public
	 * @param array $array
	 * @param callable $callback
	 * @param int $mode
	 * @return array
	 */
	public static function filter($array, $callback = null, $mode = 0)
	{
		if ( !TypeCheck::isNull($callback) ) {
			return array_filter($array,$callback,$mode);
		}
		return array_filter($array);
	}

	/**
	 * @access public
	 * @param array $array
	 * @param int $case
	 * @return array
	 */
	public static function formatKeyCase($array, $case = CASE_LOWER)
	{
		return array_change_key_case((array)$array,$case);
	}

	/**
	 * @access public
	 * @param array|object &$array
	 * @param callable $callback
	 * @param mixed $arg
	 * @return bool
	 */
	public static function walkRecursive(&$array, $callback, $arg = null)
	{
		return array_walk_recursive($array,$callback,$arg);
	}

	/**
	 * @access public
	 * @param array $array
	 * @return array
	 */
	public static function uniqueMultiple($array)
	{
		return self::map('unserialize',self::unique(
			self::map('serialize',(array)$array)
		));
	}

	/**
	 * @access public
	 * @param array $array
	 * @param string $key
	 * @return array
	 * @todo improve
	 */
	public static function uniqueMultipleByKey($array, $key) {}
	
    /**
     * @access public
     * @param array $array
     * @param mixed $orderby
     * @param string $order
     * @param bool $preserve, Preserve keys
     * @return array
     */
    public static function sort($array = [], $orderby = [], $order = 'ASC', $preserve = false)
    {
		return wp_list_sort($array,$orderby,$order,$preserve);
    }
}
