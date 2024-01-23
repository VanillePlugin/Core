<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
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
	 * @param string $file
	 * @param bool $isArray
	 * @return mixed
	 */
	public static function parse(string $file, bool $isArray = false)
	{
		return self::decode(self::r($file), $isArray);
	}

	/**
	 * Decode JSON.
	 *
	 * @access public
	 * @param string $value
	 * @param bool $isArray
	 * @return mixed
	 */
	public static function decode(string $value, bool $isArray = false)
	{
		return json_decode($value, $isArray);
	}

	/**
	 * Encode JSON without flags.
	 *
	 * @access public
	 * @param mixed $value
	 * @return mixed
	 */
	public static function encode($value)
	{
		return self::format($value, 0);
	}

	/**
	 * Encode JSON using flags,
	 * [SLASHES: 64],
	 * [PRETTY: 128],
	 * [UNICODE: 256].
	 * 
	 * @access public
	 * @param mixed $value
	 * @param int $flags
	 * @param int $depth
	 * @return mixed
	 */
	public static function format($value, int $flags = 64|256, int $depth = 512)
	{
		return json_encode($value, $flags, $depth);
	}
}
