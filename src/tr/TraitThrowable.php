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

namespace VanillePlugin\tr;

use VanillePlugin\inc\Exception;

/**
 * Define error functions.
 */
trait TraitThrowable
{
	/**
	 * Get object error message.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getError($object)
	{
		return Exception::getError($object);
	}

	/**
	 * Check object error.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isError($object) : bool
	{
		return Exception::isError($object);
	}

	/**
	 * Get last error.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getLastError()
	{
		Exception::getLastError();
	}

	/**
	 * Trigger user error.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function triggerError(string $error, int $type = 1024) : bool
	{
		return Exception::trigger($error, $type);
	}

	/**
	 * Throw error and stop execution.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function throwError(string $error)
	{
        Exception::throw($error);
	}

	/**
	 * Handle shutdown exception.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function handleException($callable)
	{
		Exception::handle($callable);
	}

	/**
	 * Clear last error.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function clearLastError()
	{
		Exception::clearLastError();
	}
}
