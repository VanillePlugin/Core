<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.4.6
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Json extends File
{
	/**
	 * @param string $path
	 */
	public function __construct($path)
	{
		parent::__construct($path);
		$this->read();
	}

	/**
	 * Parse JSON object
	 *
	 * @access public
	 * @param bool $isArray false
	 * @return mixed
	 */
	public function parse($isArray = false)
	{
		return self::decode($this->getContent(),$isArray);
	}

	/**
	 * Decode JSON
	 *
	 * @access public
	 * @param string $content
	 * @param bool $isArray false
	 * @return mixed
	 */
	public static function decode($content, $isArray = false)
	{
		return json_decode($content,$isArray);
	}

	/**
	 * Encode JSON
	 *
	 * @access public
	 * @param mixen $data
	 * @return string
	 */
	public static function encode($data)
	{
		return json_encode($data);
	}

	/**
	 * Format JSON For WordPress
	 *
	 * @access public
	 * @param mixen $data
	 * @param int $args
	 * @return string
	 *
	 * JSON_UNESCAPED_UNICODE : 256
	 * JSON_PRETTY_PRINT : 128
	 * JSON_UNESCAPED_SLASHES : 64
	 */
	public static function format($data, $args = 64|256)
	{
		return json_encode($data,$args);
	}
}
