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

namespace VanillePlugin\inc;

final class Converter
{
	/**
	 * Convert array to object.
	 *
	 * @access public
	 * @param array $array
	 * @param bool $strict
	 * @return object
	 */
	public static function toObject(array $array, $strict = false) : object
	{
		if ( $strict ) {
		    return (object)Json::decode(
		    	Json::encode($array)
		    );
		}
	    $object = new \stdClass;
	    foreach ( $array as $item => $val ) {
	        $object->{$item} = $val;
	    }
	    return (object)$object;
	}

	/**
	 * Convert object to array.
	 *
	 * @access public
	 * @param object $object
	 * @return array
	 */
	public static function toArray(object $object) : array
	{
	    return (array)Json::decode(
	    	Json::encode($object),
	    	true
	    );
	}

	/**
	 * Convert data to key.
	 *
	 * @access public
	 * @param mixed $data
	 * @return string
	 */
	public static function toKey($data) : string
	{
	    return Tokenizer::hash(
			Stringify::serialize($data)
		);
	}

	/**
	 * Convert number to money.
	 *
	 * @access public
	 * @param mixed $number
	 * @param int $decimals
	 * @param string $dSep Decimals Separator
	 * @param string $tSep Thousands Separator
	 * @return string
	 */
	public static function toMoney($number, int $decimals = 2, string $dSep = '.', string $tSep = ' ') : string
	{
		return number_format((float)$number, $decimals, $dSep, $tSep);
	}

	/**
	 * Convert dynamic value type.
	 *
	 * @access public
	 * @param mixed $value
	 * @return mixed
	 * @internal
	 */
	public static function toType($value)
	{
		if ( ($match = TypeCheck::isDynamicType('bool', $value)) ) {
			return ($match === '1') ? true : false;
		}
		if ( ($match = TypeCheck::isDynamicType('int', $value)) ) {
			return ($match !== 'NaN') ? intval($match) : '';
		}
		if ( ($match = TypeCheck::isDynamicType('float', $value)) ) {
			return ($match !== 'NaN') ? floatval($match) : '';
		}
		return $value;
	}

	/**
	 * Convert dynamic types.
	 *
	 * @access public
	 * @param mixed $values
	 * @return mixed
	 * @internal
	 */
	public static function toTypes($value)
	{
		if ( TypeCheck::isArray($value) ) {
			return Arrayify::map(function($item){
				return self::toType($item);
			}, $value);
		}
		return Converter::toType($value);
	}
}
