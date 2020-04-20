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
 */

namespace VanillePlugin\inc;

final class Json extends File
{
	/**
	 * @param inherit
	 * @return object File
	 */
	private $content;

	/**
	 * @param inherit
	 * @return object File
	 */
	public function __construct($path)
	{
		$this->content = parent::read($path);
	}

	/**
	 * @param boolean $isArray
	 * @return mixed
	 */
	public function parse($isArray = false)
	{
		return self::decode($this->content, $isArray);
	}

	/**
	 * @param string $content
	 * @param boolean $isArray
	 * @return mixed
	 */
	public static function decode($content, $isArray = false)
	{
		return json_decode($content, $isArray);
	}

	/**
	 * Format JSON For WordPress
	 *
	 * @param mixen $data
	 * @return json
	 *
	 */
	public static function encode($data)
	{
		return json_encode($data);
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
		return json_encode($data,64|128|256);
	}
}
