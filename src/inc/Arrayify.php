<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.2
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
	 * @param callable $callable
	 * @param array $array
	 * @param array $arrays
	 * @return array
	 */
	public static function map($callable, $array, $arrays = null)
	{
		if ( !TypeCheck::isNull($arrays) ) {
			return array_map($callable,(array)$array,$arrays);
		}
		return array_map($callable,(array)$array);
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
	 */
	public static function uniqueMultipleByKey($array, $key = '')
	{
		$temp = [];
		foreach ($array as &$val) {
			if ( !isset($temp[$val[$key]]) ) {
				$temp[$val[$key]] =& $val;
			}
		}
       	$array = self::values($temp);
       	return $array;
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
	 * @param callable $callable
	 * @param int $mode
	 * @return array
	 */
	public static function filter($array, $callable = null, $mode = 0)
	{
		if ( !TypeCheck::isNull($callable) ) {
			return array_filter($array,$callable,$mode);
		}
		return array_filter($array);
	}

    /**
     * @access public
     * @param array $array
     * @param mixed $orderby
     * @param string $order
     * @param bool $preserve
     * @return array
     */
    public static function sort($array = [], $orderby = [], $order = 'ASC', $preserve = false)
    {
		return wp_list_sort($array,$orderby,$order,$preserve);
    }
}
