<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.5
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Shortcode
{
	/**
	 * Get formated shortcode attributes.
	 * 
	 * @access public
	 * @param array $default
	 * @param array $atts
	 * @param string $tag
	 * @return array
	 */
	public static function attributes($default = [], $atts = [], $tag = '')
	{
		return shortcode_atts($default,$atts,$tag);
	}

	/**
	 * Format shortcode attributes.
	 * 
	 * @access public
	 * @param array $atts
	 * @return array
	 */
	public static function formatAttributes($atts = [])
	{
		$attributes = [];
		$atts = array_change_key_case((array)$atts,CASE_LOWER);
		foreach ($atts as $key => $value) {
			$key = self::formatAttribute($key);
			$attributes[$key] = $value;
		}
		return $attributes;
	}

	/**
	 * Format shortcode single attribute separator.
	 * 
	 * @access public
	 * @param string $attr
	 * @return string
	 */
	public static function formatAttribute($attr = '')
	{
		return Stringify::replace('-','_',$attr);
	}

	/**
	 * Format attribute value separator.
	 * 
	 * @access public
	 * @param string $value
	 * @param bool $spaceStrip
	 * @return string
	 */
	public static function formatSeparator($value = '', $spaceStrip = false)
	{
		if ( $spaceStrip ) {
			$value = Stringify::spaceStrip($value);
		}
		$value = Stringify::replace(';',',',$value);
		$value = Stringify::replace('|',',',$value);
		return $value;
	}

	/**
	 * Set shortcode attributes default values.
	 * 
	 * @access public
	 * @param array $atts
	 * @return array
	 */
	public static function setAttsValues($atts = [])
	{
		$values = [];
		foreach ($atts as $name) {
			$key = self::formatAttribute($name);
			$values[$key] = '';
		}
		return $values;
	}

	/**
	 * Check shortcode has attribute.
	 * 
	 * @access public
	 * @param array $atts
	 * @param string $attr
	 * @return bool
	 */
	public static function hasAttribute($atts = [], $attr = '')
	{
		$attr = self::formatAttribute($attr);
		return isset($atts[$attr]) ? true : false;
	}

	/**
	 * Check shortcode has flag attribute.
	 * 
	 * @access public
	 * @param array $atts
	 * @param string $attr
	 * @return bool
	 */
	public static function hasFlag($atts = [], $attr = '')
	{
		if ( Arrayify::hasKey(0,$atts) ) {
			if ( $atts[0] == $attr ) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Get shortcode attribute value.
	 * 
	 * @access public
	 * @param array $atts
	 * @param string $attr
	 * @param string $type
	 * @return mixed
	 */
	public static function getValue($atts = [], $attr = '', $type = null)
	{
		$attr = self::formatAttribute($attr);
		if ( isset($atts[$attr]) ) {
			$value = $atts[$attr];
			if ( $type == 'int' || $type == 'integer' ) {
				$value = intval($value);
			} elseif ( $type == 'float' || $type == 'double' ) {
				$value = floatval($value);
			}
			return $value;
		}
		return false;
	}

	/**
	 * Check shortcode attribute value.
	 * 
	 * @access public
	 * @param array $atts
	 * @param string $attr
	 * @param string $value
	 * @return bool
	 */
	public static function hasValue($atts = [], $attr = '', $value = '')
	{
		$attr = self::formatAttribute($attr);
		if ( isset($atts[$attr]) ) {
			$val = Stringify::lowercase($atts[$attr]);
			$value = Stringify::lowercase($value);
			if ( $val == $value ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check shortcode attribute empty to allow override.
	 * 
	 * @access public
	 * @param array $atts
	 * @param string $attr
	 * @return bool
	 */
	public static function isEmpty($atts = [], $attr = '')
	{
		$attr = self::formatAttribute($attr);
		if ( isset($atts[$attr]) ) {
			if ( empty($atts[$attr]) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check shortcode attribute disabled.
	 * 
	 * @access public
	 * @param array $atts
	 * @param string $attr
	 * @return bool
	 */
	public static function isDisabled($atts = [], $attr = '')
	{
		$attr = self::formatAttribute($attr);
		if ( isset($atts[$attr]) ) {
			$value = Stringify::lowercase($atts[$attr]);
			if ( Stringify::contains(['off','no','non','false','0'],$value) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check shortcode attribute enabled.
	 * 
	 * @access public
	 * @param array $atts
	 * @param string $attr
	 * @return bool
	 */
	public static function isEnabled($atts = [], $attr = '')
	{
		$attr = self::formatAttribute($attr);
		if ( isset($atts[$attr]) ) {
			$value = Stringify::lowercase($atts[$attr]);
			if ( Stringify::contains(['on','yes','oui','true','1'],$value) ) {
				return true;
			}
		}
		return false;
	}
}
