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

namespace VanillePlugin\tr;

use VanillePlugin\inc\Stringify;

trait TraitSerializable
{
	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function serialize($value)
    {
        return Stringify::serialize($value);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function unserialize(string $value)
    {
        return Stringify::unserialize($value);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function isSerialized(string $value) : bool
    {
        return Stringify::isSerialized($value);
    }
}
