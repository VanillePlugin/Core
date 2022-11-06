<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.1
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
	 * @return object
	 */
	public static function toObject($array)
	{
	    if ( empty($array) || !TypeCheck::isArray($array) ) {
	    	return (object)$array;
	    }
	    $obj = new \stdClass;
	    foreach ( $array as $key => $value ) {
	        $obj->{$key} = $value;
	    }
	    return (object)$obj;
	}
	
	/**
	 * Convert number to money.
	 * 
	 * @access public
	 * @param mixed $number
	 * @param int $decimals
	 * @param string $dSep Decimals Separator
	 * @param string $tSep Thousands Separator
	 * @return float
	 */
	public static function toMoney($number, $decimals = 2, $dSep = '.', $tSep = ' ')
	{
		return number_format((float)$number,$decimals,$dSep,$tSep);
	}
}
