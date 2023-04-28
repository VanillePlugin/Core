<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

/**
 * Advanced shortcode helper.
 */
final class Shortcode
{
	/**
	 * Get shortcode attributes.
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
		$atts = Arrayify::formatKeyCase($atts);
		foreach ($atts as $key => $value) {
			if ( TypeCheck::isString($key) ) {
				$key = self::formatAttributeName($key);
			}
			$attributes[$key] = $value;
		}
		return $attributes;
	}

	/**
	 * Format shortcode attribute name.
	 * 
	 * @access public
	 * @param string $attr
	 * @return string
	 */
	public static function formatAttributeName($attr = '')
	{
		return Stringify::replace('-','_',Stringify::lowercase($attr));
	}

	/**
	 * Format attribute value separator.
	 * 
	 * @access public
	 * @param string $value
	 * @param bool $strip, Strip space
	 * @return string
	 */
	public static function formatSeparator($value = '', $strip = false)
	{
		if ( $strip ) {
			$value = Stringify::stripSpace($value);
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
		foreach ($atts as $key => $name) {
			$values[$name] = '';
		}
		return self::formatAttributes($values);
	}

	/**
	 * Check shortcode has attribute (Not flag attribut).
	 * 
	 * @access public
	 * @param array $atts
	 * @param string $attr
	 * @return bool
	 */
	public static function hasAttribute($atts = [], $attr = '')
	{
		$attr = self::formatAttributeName($attr);
		$atts = self::formatAttributes($atts);
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
		$flags = [];
		$attr = self::formatAttributeName($attr);
		foreach ($atts as $key => $name) {
			if ( TypeCheck::isInt($key) && TypeCheck::isString($name) ) {
				$flags[] = self::formatAttributeName($name);
			}
		}
		return Arrayify::inArray($attr,$flags);
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
		$attr = self::formatAttributeName($attr);
		$atts = self::formatAttributes($atts);
		if ( isset($atts[$attr]) ) {
			$value = $atts[$attr];

			switch ($type) {
				case 'int':
				case 'integer':
					$value = intval($value);
					break;

				case 'float':
				case 'double':
					$value = floatval($value);
					break;

				case 'bool':
				case 'boolean':
					$value = boolval($value);
					break;
			}

			return $value;
		}

		return null;
	}

	/**
	 * Check shortcode attribute value.
	 * 
	 * @access public
	 * @param array $atts
	 * @param string $attr
	 * @param mixed $value
	 * @return bool
	 */
	public static function hasValue($atts = [], $attr = '', $value = '')
	{
		$attr = self::formatAttributeName($attr);
		$atts = self::formatAttributes($atts);
		if ( isset($atts[$attr]) ) {
			$val = $atts[$attr];
			if ( TypeCheck::isString($val) ) {
				$val = Stringify::lowercase($val);
			}
			if ( TypeCheck::isString($value) ) {
				$value = Stringify::lowercase($value);
			}
			return ($val === $value);
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
		$attr = self::formatAttributeName($attr);
		$atts = self::formatAttributes($atts);
		if ( isset($atts[$attr]) ) {
			if ( $atts[$attr] === '0' || $atts[$attr] === 0 ) {
				return false;
			}
			return empty($atts[$attr]);
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
		$attr = self::formatAttributeName($attr);
		$atts = self::formatAttributes($atts);
		if ( isset($atts[$attr]) ) {
			$value = Stringify::lowercase($atts[$attr]);
			return Stringify::contains(['off','no','non','false'],$value);
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
		$attr = self::formatAttributeName($attr);
		$atts = self::formatAttributes($atts);
		if ( isset($atts[$attr]) ) {
			$value = Stringify::lowercase($atts[$attr]);
			return Stringify::contains(['on','yes','oui','true'],$value);
		}
		return false;
	}

	/**
	 * Search shortcodes in content,
	 * And filter shortcodes through their hooks.
	 *
	 * @access public
	 * @param string $content
	 * @param bool $ignore, Ignore HTML
	 * @return string
	 */
	public static function do($content, $ignore = false)
	{
		return do_shortcode($content,$ignore);
	}

	/**
	 * Render shortcodes.
	 *
	 * @access public
	 * @param string $content
	 * @param bool $ignore, Ignore HTML
	 * @return void
	 */
	public static function render($content, $ignore = false)
	{
		echo self::do($content,$ignore);
	}
	
	/**
	 * Checks whether content contains shortcode.
	 *
	 * @access public
	 * @param string $content
	 * @param string $tag
	 * @return bool
	 */
	public static function has($content, $tag)
	{
		return has_shortcode($content,$tag);
	}

	/**
	 * strip content from shortcodes.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public static function strip($content)
	{
		return strip_shortcodes($content);
	}
}
