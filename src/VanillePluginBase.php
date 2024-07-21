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

namespace VanillePlugin;

/**
 * Define base functions.
 *
 * - Hooking
 * - Rendering
 * - Authentication
 *
 * @see https://developer.wordpress.org/
 */
trait VanillePluginBase
{
	use \VanillePlugin\tr\TraitHookable,
		\VanillePlugin\tr\TraitRenderable,
		\VanillePlugin\tr\TraitAuthenticatable;
}
