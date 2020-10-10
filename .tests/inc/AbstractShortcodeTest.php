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

use VanillePluginTest\int\ShortcodeInterfaceTest;
use VanillePluginTest\lib\ViewTest;

abstract class AbstractShortcodeTest extends ViewTest implements ShortcodeInterfaceTest
{
	/**
	 * @access public
	 * @param array $atts
	 * @param string $content null
	 * @param string $tag
	 * @return string
	 */
	abstract public function doCallable($atts = [], $content = null, $tag = '');

	/**
	 * @access protected
	 * @param array $default
	 * @param array $atts
	 * @param string $shortcode
	 * @return array
	 */
	protected function attributes($default = [], $atts = [], $shortcode = '')
	{
		return shortcode_atts($default, $atts, $shortcode);
	}

	/**
	 * @access protected
	 * @param array $atts
	 * @return array
	 */
	protected function formatAtts($atts = [])
	{
		return array_change_key_case((array)$atts, CASE_LOWER);
	}
}
