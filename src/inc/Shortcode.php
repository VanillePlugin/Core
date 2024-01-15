<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

final class Shortcode
{
	/**
	 * Add shortcode.
	 *
	 * @access public
	 * @param string $tag
	 * @param callable $callback
	 * @return void
	 */
	public static function add(string $tag, $callback)
	{
		add_shortcode($tag, $callback);
	}

	/**
	 * Remove shortcode.
	 *
	 * @access public
	 * @param string $tag
	 * @return void
	 */
	public static function remove(string $tag)
	{
		remove_shortcode($tag);
	}

	/**
	 * Check whether shortcode exists.
	 *
	 * @access public
	 * @param string $tag
	 * @return bool
	 */
	public static function exists(string $tag) : bool
	{
		return shortcode_exists($tag);
	}

	/**
	 * Assign content to shortcode.
	 *
	 * @access public
	 * @param string $content
	 * @param bool $ignore, Ignore HTML
	 * @return string
	 */
	public static function do(string $content, bool $ignore = false) : string
	{
		return do_shortcode($content, $ignore);
	}

	/**
	 * Render shortcode.
	 *
	 * @access public
	 * @param string $content
	 * @param bool $ignore, Ignore HTML
	 * @return void
	 */
	public static function render(string $content, bool $ignore = false)
	{
		echo self::do($content, $ignore);
	}
	
	/**
	 * Check whether content has shortcode.
	 *
	 * @access public
	 * @param string $content
	 * @param string $tag
	 * @return bool
	 */
	public static function has(string $content, string $tag) : bool
	{
		return has_shortcode($content, $tag);
	}

	/**
	 * Strip content from shortcodes.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public static function strip(string $content) : string
	{
		return Stringify::unShortcode($content);
	}
	
	/**
	 * Get shortcode attributes.
	 * 
	 * @access public
	 * @param array $default
	 * @param array $atts
	 * @param string $tag
	 * @return array
	 */
	public static function attributes(array $default = [], array $atts = [], ?string $tag = null) : array
	{
		return shortcode_atts($default, $atts, (string)$tag);
	}

	/**
	 * Format shortcode attributes.
	 * 
	 * @access public
	 * @param array $atts
	 * @return array
	 */
	public static function formatAttributes(array $atts) : array
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
	public static function formatAttributeName(string $attr) : string
	{
		return Stringify::undash(
			Stringify::lowercase($attr)
		);
	}

	/**
	 * Format attribute value separator.
	 * 
	 * @access public
	 * @param string $value
	 * @param bool $strip, Strip space
	 * @return string
	 */
	public static function formatSeparator(string $value, bool $strip = false)
	{
		if ( $strip ) {
			$value = Stringify::stripSpace($value);
		}
		$value = Stringify::replace(';', ',', $value);
		$value = Stringify::replace('|', ',', $value);
		return $value;
	}

	/**
	 * Set shortcode attributes default values.
	 * 
	 * @access public
	 * @param array $atts
	 * @return array
	 */
	public static function setAttsValues(array $atts) : array
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
	public static function hasAttribute(array $atts, string $attr) : bool
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
	public static function hasFlag(array $atts, string $attr) : bool
	{
		$flags = [];
		$attr = self::formatAttributeName($attr);
		foreach ($atts as $key => $name) {
			if ( TypeCheck::isInt($key) && TypeCheck::isString($name) ) {
				$flags[] = self::formatAttributeName($name);
			}
		}
		return Arrayify::inArray($attr, $flags);
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
	public static function getValue(array $atts, string $attr, ?string $type = null)
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
	public static function hasValue(array $atts, string $attr, $value) : bool
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
	public static function isEmpty(array $atts, string $attr) : bool
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
	public static function isDisabled(array $atts, string $attr) : bool
	{
		$attr = self::formatAttributeName($attr);
		$atts = self::formatAttributes($atts);
		if ( isset($atts[$attr]) ) {
			$value = Stringify::lowercase((string)$atts[$attr]);
			return Stringify::contains(['off', 'no', 'non', 'false'], $value);
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
	public static function isEnabled(array $atts, string $attr) : bool
	{
		$attr = self::formatAttributeName($attr);
		$atts = self::formatAttributes($atts);
		if ( isset($atts[$attr]) ) {
			$value = Stringify::lowercase((string)$atts[$attr]);
			return Stringify::contains(['on', 'yes', 'oui', 'true'], $value);
		}
		return false;
	}
}
