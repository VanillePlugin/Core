<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface ShortcodeInterface
{
	/**
	 * Init shortcode(s).
	 * [Action: front-init].
	 *
	 * @return void
	 */
	function init();

	/**
	 * Do shortcode(s) callable.
	 * [slug].
	 *
	 * @access public
	 * @param array $atts
	 * @param string $content
	 * @param string $tag
	 * @return string
	 */
	function doCallable(array $atts = [], ?string $content = null, ?string $tag = null) : string;
}
