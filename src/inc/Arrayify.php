<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.4
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Arrayify
{
	/**
	 * @access public
	 * @param mixed $needle
	 * @param array $haystack
	 * @param bool $strict
	 * @return bool
	 */
	public static function inArray($needle, $haystack, $strict = false)
	{
		return in_array($needle,$haystack,$strict);
	}

	/**
	 * @access public
	 * @param array $array
	 * @param array $arrays
	 * @return array
	 */
	public static function merge($array, $arrays)
	{
		return array_merge($array,$arrays);
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
		return array_combine($keys,$values);
	}

	/**
	 * @access public
	 * @param mixed $callback
	 * @param array $array
	 * @param array $arrays
	 * @return array
	 */
	public static function map($callback = null, $array, $arrays)
	{
		return array_map($callback,$array,$arrays);
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
	 * @param string $key
	 * @return array
	 */
	public static function uniqueMultiple($array, $key = '')
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
	public static function filter($array, $callable = null, $mode = null)
	{
		if ( $callable ) {
			return array_filter($array,$callable,$mode);
		}
		return array_filter($array);
	}

    /**
     * @access public
     * @param array $array
     * @param array $args
     * @return array
     */
    public static function order($array = [], $args = [], $flags = 0)
    {
    	$args = self::merge([
    		'sort'   => 'asc',
    		'column' => false
    	], (array)$args);

    	if ( TypeCheck::isString($args['column']) ) {

    		// Order by values
    		$ordered = $array;
    		$excluded = [];
    		if ( isset($args['exclude']) ) {
    			$ordered = $array;
    			foreach ($ordered as $key => $item) {
    				if ( isset($item[$args['column']]) ) {
    					if ( $item[$args['column']] == $args['exclude'] ) {
    						$excluded[$key] = $item;
    						unset($ordered[$key]);
    					}
    				}
    			}
    		}

    		if ( count($ordered) > 1 ) {
				usort($ordered,function($a, $b) use ($args) {
					$column = $args['column'];
					if ( isset($a[$column]) && isset($b[$column]) ) {
						// Order
						if ( Stringify::lowercase($args['sort']) == 'asc' ) {
					   		return (int)($a[$column] >= $b[$column]) && ($b[$column] <= $a[$column]);

						} elseif ( Stringify::lowercase($args['sort']) == 'desc' ) {
					   		return (int)($a[$column] <= $b[$column]) && ($b[$column] >= $a[$column]);
						}
					}
				});
    		}
    		$array = $ordered;
    		$array = self::merge($array,$excluded);

    	} else {

    		// Order
	    	if ( Stringify::lowercase($args['sort']) == 'asc' ) {
	    		asort($array,$flags);

	    	} elseif ( Stringify::lowercase($args['sort']) == 'desc' ) {
	    		arsort($array,$flags);
	    	}
    	}

        return $array;
    }
}
