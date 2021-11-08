<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.5
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class TypeCheck
{
	/**
	 * @access public
	 * @param mixed $data
	 * @return bool
	 */
	public static function isString($data)
	{
		return is_string($data);
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @return bool
	 */
	public static function isObject($data)
	{
		return is_object($data);
	}
	
	/**
	 * @access public
	 * @param mixed $data
	 * @return bool
	 */
	public static function isArray($data)
	{
		return is_array($data);
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @param bool $string
	 * @return bool
	 */
	public static function isInt($data, $string = false)
	{
		if ( $string ) {
			return is_numeric($data);
		}
		return is_int($data);
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @param bool $string
	 * @return bool
	 */
	public static function isFloat($data, $string = false)
	{
		if ( $string ) {
			$data = (float)$data;
		}
		return is_float($data);
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @return bool
	 */
	public static function isBool($data)
	{
		return is_bool($data);
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @return bool
	 */
	public static function isNull($data)
	{
		return is_null($data);
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @return bool
	 */
	public static function isNan($data)
	{
		return is_nan($data);
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @return bool
	 */
	public static function isCallable($data)
	{
		return is_callable($data);
	}

	/**
	 * @access public
	 * @param string $function
	 * @return bool
	 */
	public static function isFunction($function)
	{
		return function_exists($function);
	}

	/**
	 * @access public
	 * @param string $class
	 * @return bool
	 */
	public static function isClass($class)
	{
		return class_exists($class);
	}

	/**
	 * @access public
	 * @param string $sub
	 * @param string $class
	 * @return bool
	 */
	public static function isSubClassOf($sub, $class)
	{
		return is_subclass_of($sub,$class);
	}

	/**
	 * @access public
	 * @param string $class
	 * @param string $interface
	 * @return bool
	 */
	public static function hasInterface($class, $interface)
	{
		$interfaces = class_implements($class);
		return Stringify::contains($interfaces,$interface);
	}

	/**
	 * @access public
	 * @param object $object
	 * @param string $method
	 * @return bool
	 */
	public static function hasMethod($object, $method)
	{
		return method_exists($object,$method);
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @return bool
	 */
	public static function isCountable($data)
	{
		return is_countable($data);
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @return bool
	 */
	public static function isResource($data)
	{
		return is_resource($data);
	}

	/**
	 * @access public
	 * @param mixed $data
	 * @return bool
	 */
	public static function isScalar($data)
	{
		return is_scalar($data);
	}

	/**
	 * @access public
	 * @param string $path
	 * @return bool
	 */
	public static function isStream($path)
	{
	    $scheme = strpos($path,'://');
	    if ( false === $scheme ) {
	        return false;
	    }
	    $stream = substr($path,0,$scheme);
	    return Arrayify::inArray($stream,stream_get_wrappers(),true);
	}
}
