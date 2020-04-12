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

namespace winamaz\core\system\includes;

class Json
{
	/**
	 * @param string $json
	 * @param boolean $object
	 * @return array|object
	 */
	public static function decode($json, $object = false)
	{
		if ($object) {
			return json_decode($json, false);
		} else {
			return json_decode($json, true);
		}
	}

	/**
	 * Format JSON For WordPress
	 *
	 * @param mixen $data
	 * @return json
	 *
	 * JSON_UNESCAPED_UNICODE : 256
	 * JSON_UNESCAPED_SLASHES : 64
	 * JSON_PRETTY_PRINT : 64
	 */
	public static function format($data)
	{
		return json_encode($data, 64|128|256);
	}

	/**
	 * Strip Slashes
	 *
	 * @param mixen $data
	 * @return json
	 */
	public static function stripSlash($data)
	{
		return stripslashes_deep($data);
	}

	/**
	 * @param $json
	 * @return json
	 */
	public static function clean($json, $single = null)
	{
		$json = str_replace('\\\\', '\\', $json);
		if ($single) {
			$json = str_replace('\\', '', $json);
		}
		return $json;
	}
}
