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

namespace VanillePlugin\inc;

use \WP_Error;

/**
 * Exception and error handler helper.
 */
final class Exception extends \Exception
{
	/**
	 * Handle shutdown exception.
	 *
	 * @access public
	 * @param callable $callback
	 * @param array $args
	 * @return bool
	 */
	public static function handle(callable $callback, ?array $args = null) : bool
	{
		return (bool)register_shutdown_function($callback, $args);
	}

	/**
	 * Get last error.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getLastError()
	{
		return error_get_last();
	}

	/**
	 * Clear last error.
	 *
	 * @access public
	 * @return void
	 */
	public static function clearLastError()
	{
		error_clear_last();
	}

	/**
	 * Trigger user error.
	 *
	 * [E_USER_NOTICE: 1024]
	 *
	 * @access public
	 * @param string $error
	 * @param int $type
	 * @return bool
	 */
	public static function trigger(string $error, int $type = 1024) : bool
	{
		return trigger_error($error, $type);
	}

	/**
	 * Return error object.
	 *
	 * @access public
	 * @param mixed $code
	 * @param string $message
	 * @param mixed $data
	 * @return object
	 */
	public static function error($code, ?string $message = null, $data = []) : object
	{
	    return new WP_Error($code, $message, $data);
	}

	/**
	 * Throw error and stop execution.
	 *
	 * @access public
	 * @param mixed $message
	 * @param mixed $title
	 * @param mixed $args
	 * @return void
	 */
	public static function throw($message = '', $title = '', $args = [])
	{
		wp_die($message, $title, $args);
	}

	/**
	 * Stop execution.
	 *
	 * @access public
	 * @return void
	 */
	public static function die()
	{
		self::throw();
	}

	/**
	 * Get object error message.
	 *
	 * @access public
	 * @param mixed $object
	 * @return mixed
	 */
	public static function getError($object)
	{
		if ( self::isError($object) ) {
			return $object->get_error_message();
		}
		return false;
	}

	/**
	 * Check object error.
	 *
	 * @access public
	 * @param mixed $object
	 * @return bool
	 */
	public static function isError($object) : bool
	{
		return is_wp_error($object);
	}
}
