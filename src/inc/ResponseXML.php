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

class ResponseXML
{
	public static function format($xml)
	{
		$xml = str_replace('<?xml version="1.0" encoding="utf-8" ?>', '', $xml);
		$xml = str_replace('</xml>', '', $xml);
		return $xml;
	}
	public static function parse($xml)
	{
		return simplexml_load_string($xml);
	}
}
