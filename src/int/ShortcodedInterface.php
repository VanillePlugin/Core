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

interface ShortcodedInterface
{
	/**
	 * Get shortcode part templates.
	 *
	 * @return array
	 */
	static function templates() : array;

	/**
	 * Get shortcode part attributes.
	 *
	 * @return array
	 */
	static function atts() : array;
}
