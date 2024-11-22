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

namespace VanillePlugin\tr;

use VanillePlugin\inc\Stringify;

/**
 * Define serializing functions.
 */
trait TraitSerializable
{
	/**
	 * Serialize value if not serialized.
	 *
	 * @access public
	 * @inheritdoc
	 */
    public function serialize($value)
    {
        return Stringify::serialize($value);
    }

	/**
	 * Unserialize serialized value.
	 *
	 * @access public
	 * @inheritdoc
	 */
    public function unserialize(string $value)
    {
        return Stringify::unserialize($value);
    }

	/**
	 * Check serialized value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isSerialized(string $value) : bool
    {
        return Stringify::isSerialized($value);
    }

	/**
	 * Check serialized value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function toString(string $value) : bool
    {
        return $this->__toString();
    }
}
