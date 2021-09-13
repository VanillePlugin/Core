<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.0
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
	 * @access public
	 * @param array $default
	 * @param array $atts
	 * @param string $shortcode
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
	public static function formatAtts($atts = [])
	{
		return array_change_key_case((array)$atts,CASE_LOWER);
	}
}
