<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
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
}
