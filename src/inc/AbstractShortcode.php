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

use VanillePlugin\int\ShortcodeInterface;
use VanillePlugin\lib\View;

abstract class AbstractShortcode extends View implements ShortcodeInterface
{
	/**
	 * Do shortcode main callable.
	 * 
	 * @access public
	 * @param array $atts
	 * @param string $content
	 * @param string $tag
	 * @return string
	 */
	abstract public function doCallable($atts = [], $content = null, $tag = '');

	/**
	 * @access protected
	 * @param array $default
	 * @param array $atts
	 * @param string $tag
	 * @return array
	 */
	protected function attributes($default = [], $atts = [], $tag = '')
	{
		return Shortcode::attributes($default,$atts,$tag);
	}

	/**
	 * Format shortcode attributes.
	 * 
	 * @access protected
	 * @param array $atts
	 * @return array
	 */
	protected function formatAtts($atts = [])
	{
		return Shortcode::formatAtts($atts);
	}
}
