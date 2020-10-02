<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.2
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
	 * @access private
	 * @var string content
	 */
	private $content;

	/**
	 * @param string $path
	 * @return void
	 */
	public function __construct($path)
	{
		$this->content = parent::read($path);
	}

	/**
	 * @access public
	 * @param boolean $isArray
	 * @return mixed
	 */
	public function parse($isArray = false)
	{
		return self::decode($this->content, $isArray);
	}

	/**
	 * @access public
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
	 * @access public
	 * @param mixen $data
	 * @return json
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
