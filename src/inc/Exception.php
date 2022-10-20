<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

use \WP_Error;

/**
 * Exception|Error Handler Helper.
 */
class Exception extends \Exception
{
	/**
	 * Handle shutdown exception.
	 *
	 * @access public
	 * @var array $callable
	 * @return void
	 */
	public static function handle($callable)
	{
		register_shutdown_function($callable);
	}

	/**
	 * Get last error.
	 *
	 * @access public
	 * @var void
	 * @return string
	 */
	public static function getLastError()
	{
		return error_get_last();
	}

	/**
	 * Clear last error.
	 *
	 * @access public
	 * @var void
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
	 * @var string $message
	 * @var int $type
	 * @return bool
	 */
	public static function trigger($message, $type = E_USER_NOTICE)
	{
		return trigger_error($message,$type);
	}

	/**
	 * Return WordPress error object.
	 *
	 * @access public
	 * @param string $code
	 * @param string $message
	 * @param array $args
	 * @return object
	 */
	public static function error($code = '', $message = '', $args = [])
	{
	    return new WP_Error($code,$message,$args);
	}

	/**
	 * Kill WordPress execution and display HTML message with error message.
	 *
	 * @access public
	 * @param string $message
	 * @param string $title
	 * @param array $args
	 * @return void
	 */
	public static function except($message = '', $title = '', $args = [])
	{
		wp_die($message,$title,$args);
	}

	/**
	 * Check for WordPress error.
	 *
	 * @access public
	 * @param mixed $object
	 * @return void
	 */
	public static function isError($object)
	{
		return is_wp_error($object);
	}
}
