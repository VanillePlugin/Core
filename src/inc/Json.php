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

final class Json extends File
{
	/**
	 * Parse JSON file.
	 *
	 * @access public
	 * @param bool $isArray
	 * @return mixed
	 */
	public static function parse($file, $isArray = false)
	{
		return self::decode(self::r($file),$isArray);
	}

	/**
	 * Decode JSON.
	 *
	 * @access public
	 * @param string $content
	 * @param bool $isArray
	 * @return mixed
	 */
	public static function decode($content, $isArray = false)
	{
		return json_decode((string)$content,(bool)$isArray);
	}

	/**
	 * Encode JSON.
	 *
	 * @access public
	 * @param mixen $value
	 * @return string
	 */
	public static function encode($value)
	{
		return self::format($value,0);
	}

	/**
	 * Format JSON for WordPress.
	 * 
	 * JSON_UNESCAPED_UNICODE: 256
	 * JSON_PRETTY_PRINT: 128
	 * JSON_UNESCAPED_SLASHES: 64
	 *
	 * @access public
	 * @param mixed $value
	 * @param int $flags
	 * @param int $depth
	 * @return mixed
	 */
	public static function format($value, $flags = 64|256, $depth = 512)
	{
		return json_encode($value,$flags,$depth);
	}
}
