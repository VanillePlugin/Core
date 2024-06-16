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

trait TraitThrowable
{
	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function handleException($callable)
	{
		Exception::handle($callable);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getLastError()
	{
		Exception::getLastError();
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function clearLastError()
	{
		Exception::clearLastError();
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function triggerError(string $error, int $type = E_USER_NOTICE) : bool
	{
		return Exception::trigger($error, $type);
	}
	
	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function throwError(string $error)
	{
        Exception::throw($error);
	}
}
