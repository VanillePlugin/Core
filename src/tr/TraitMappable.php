<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\Arrayify;

/**
 * Define mapping functions.
 */
trait TraitMappable
{
	/**
	 * Map array.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function map($callback, array $array, ?array $arrays = null): array
	{
		return Arrayify::map($callback, $array, $arrays);
	}

	/**
	 * Walk recursive array.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function recursiveArray(&$array, $callback, $arg = null): bool
	{
		return Arrayify::recursive($array, $callback, $arg);
	}
}
