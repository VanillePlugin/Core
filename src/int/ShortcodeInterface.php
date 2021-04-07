<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.4
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\int;

interface ShortcodeInterface
{
    /**
     * @param void
     * @return void
     */
	function init();

	/**
	 * @param array $params
	 * @param string $content
	 * @param string $tag
	 * @return string
	 */
	function doCallable($atts = [], $content = null, $tag = '');
}
