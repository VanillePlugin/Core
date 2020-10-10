<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.6
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePluginTest\inc;

final class ResponseXMLTest
{
	/**
	 * @access public 
	 * @param string $xml
	 * @return string
	 */
	public static function format($xml)
	{
		$xml = str_replace('<?xml version="1.0" encoding="utf-8" ?>', '', $xml);
		$xml = str_replace('</xml>', '', $xml);
		return $xml;
	}
	
	/**
	 * @access public 
	 * @param string $xml
	 * @param int $args
	 * @return string
	 *
	 * LIBXML_NOCDATA : 16384
	 * LIBXML_VERSION : 20908
	 */
	public static function parse($xml, $args = 16384|20908)
	{
		return simplexml_load_string($xml, 'SimpleXMLElement', $args);
	}

	/**
	 * @access public 
	 * @param string $xml
	 * @param int $args
	 * @return string
	 */
	public static function parseFile($xml, $args = 16384|20908)
	{
		return simplexml_load_file($xml, 'SimpleXMLElement', $args);
	}
}
