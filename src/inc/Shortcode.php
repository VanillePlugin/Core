<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
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
	 * @return mixed
	 */
	public static function add(string $tag, $callback)
	{
		return add_shortcode($tag, $callback);
	}

	/**
	 * Remove shortcode.
	 *
	 * @access public
	 * @param string $tag
	 * @return mixed
	 */
	public static function remove(string $tag)
	{
		return remove_shortcode($tag);
	}

	/**
	 * Check whether shortcode is registered.
	 *
	 * @access public
	 * @param string $tag
	 * @return bool
	 */
	public static function has(string $tag) : bool
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
	public static function contains(string $content, string $tag) : bool
	{
		return has_shortcode($content, $tag);
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
	public static function getAtts(array $default = [], array $atts = [], ?string $tag = null) : array
	{
		return shortcode_atts($default, $atts, (string)$tag);
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
}
