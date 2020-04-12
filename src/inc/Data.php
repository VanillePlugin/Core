<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 * Allowed to edit for plugin customization
 */

namespace VanillePlugin\lib;

use VanilleNameSpace\core\system\libraries\interfaces\ConfigInterface;

class Data
{
	/**
	 * @since 4.0.0
	 * @version 5.4
	 * @access public
	 * @param array $array
	 * @return object
	 */
	public static function toObject($array = [])
	{
	    if ( empty($array) || !is_array($array) ) {
	    	return false;
	    }
	    $obj = new \stdClass;
	    foreach ( $array as $key => $val ) {
	        $obj->{$key} = $val;
	    }
	    return $obj;
	}

	/**
	 * Deeply strip slashes
	 *
	 * @see /Function_Reference/stripslashes_deep/
	 * @since 4.0.0
	 * @version 5.4
	 * @access public
	 * @param mixed $data
	 * @return mixed
	 */
	public static function slashStrip($data)
	{
		$data = stripslashes_deep($data);
	    return $data;
	}
}
