<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface LoggerInterface
{
    /**
     * Init logger.
     * 
     * @param string $path
     * @param string $filename
     * @param string $extension
     */
    function __construct(?string $path = '/', string $file = 'debug', string $ext = 'log');

    /**
     * Set log path.
     *
     * @param string $path
     * @return object
     */
    function setPath(string $path) : self;

    /**
     * Set log filename.
     *
     * @param string $filename
     * @return object
     */
    function setFilename(string $filename) : self;

    /**
     * Set log extension.
     *
     * @param string $extension
     * @return object
     */
    function setExtension(string $extension) : self;

    /**
     * Log debug message.
     *
     * @param mixed $message
     * @param bool $isArray
     * @return bool
     */
    function debug($message, bool $isArray = false) : bool;

    /**
     * Log error message.
     *
     * @param string $message
     * @return bool
     */
    function error(string $message) : bool;

    /**
     * Log warning message.
     *
     * @param string $message
     * @return bool
     */
    function warning(string $message) : bool;

    /**
     * Log info message.
     *
     * @param string $message
     * @return bool
     */
    function info(string $message) : bool;

    /**
     * Log custom message.
     *
     * @param string $message
     * @param string $type
     * @return bool
     */
    function custom(string $message, string $type = 'custom') : bool;

    /**
     * Log natif error.
     *
     * @param string $message
     * @param int $type 0
     * @param string $path
     * @param string $headers
     * @return bool
     */
    static function log(string $message, int $type = 0, ?string $path = null, ?string $headers = null) : bool;
}
