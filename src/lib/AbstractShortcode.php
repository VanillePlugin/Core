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

namespace VanillePlugin\lib;

use VanillePlugin\int\ShortcodeInterface;
use VanillePlugin\inc\Shortcode;

/**
 * Wrapper class for shortcode.
 */
abstract class AbstractShortcode extends View implements ShortcodeInterface
{
	/**
	 * @inheritdoc
	 */
	abstract public function doCallable(array $atts = [], ?string $content = null, ?string $tag = null) : string;

	/**
	 * Get shortcode attributes.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function attributes(array $default = [], array $atts = [], ?string $tag = null) : array
	{
		return Shortcode::attributes($default, $atts, $tag);
	}

	/**
	 * Format shortcode attributes.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function formatAttributes(array $atts = []) : array
	{
		return Shortcode::formatAttributes($atts);
	}
}
