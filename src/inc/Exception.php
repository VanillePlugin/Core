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
	 * @access public
	 * @param string $error
	 * @param int $type
	 * @return bool
	 */
	public static function trigger(string $error, int $type = E_USER_NOTICE) : bool
	{
		return trigger_error($error, $type);
	}

	/**
	 * Return WordPress error object.
	 *
	 * @access public
	 * @param mixed $code
	 * @param string $message
	 * @param array $args
	 * @return object
	 */
	public static function error($code, ?string $message = null, array $args = []) : object
	{
	    return new WP_Error($code, $message, $args);
	}

	/**
	 * Kill WordPress execution,
	 * Display optional message.
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
	 * Kill WordPress execution.
	 *
	 * @access public
	 * @return void
	 */
	public static function die()
	{
		self::throw();
	}

	/**
	 * Check for WordPress error.
	 *
	 * @access public
	 * @param mixed $object
	 * @return bool
	 */
	public static function isError($object) : bool
	{
		return is_wp_error($object);
	}

	/**
	 * Get WordPress error.
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
}
