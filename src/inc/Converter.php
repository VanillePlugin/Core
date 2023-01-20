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
	    return (object)Json::decode(
	    	Json::encode($array),
	    	false
	    );
	}

	/**
	 * Convert object to array.
	 * 
	 * @access public
	 * @param object $object
	 * @return array
	 */
	public static function toArray($object)
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
	 * @return mixed
	 */
	public static function toMoney($number, $decimals = 2, $dSep = '.', $tSep = ' ')
	{
		return number_format((float)$number,$decimals,$dSep,$tSep);
	}
}
