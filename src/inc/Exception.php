<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.6
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use \Exception as MainException;

class Exception extends MainException
{
	/**
	 * Handle shutdown exception
	 *
	 * @access protected
	 * @var array $callable
	 * @return void
	 */
	protected function shutdown($callable)
	{
		register_shutdown_function($callable);
	}

	/**
	 * Get last error
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
	 * Clear last error
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
	 * Trigger user error
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
	 * Log user error
	 *
     * @access public
     * @param string $message
     * @param string $type
     * @param string $path
     * @param array $headers
     * @return bool
     */
    public function log($message = '', $type = 0, $path = null, $headers = null)
    {
        return error_log($message,$type,$path,$headers);
    }
}
