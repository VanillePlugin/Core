<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.5
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use \Exception as MainException;
use \WP_Error;

class Exception extends MainException
{
	/**
	 * Handle shutdown exception.
	 *
	 * @access public
	 * @var array $callable
	 * @return void
	 */
	public function handle($callable)
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
	public function getLastError()
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
	public function clearLastError()
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
	public function trigger($message, $type = E_USER_NOTICE)
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
	public function error($code = '', $message = '', $args = [])
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
	public function except($message = '', $title = '', $args = [])
	{
		wp_die($message,$title,$args);
	}
}
