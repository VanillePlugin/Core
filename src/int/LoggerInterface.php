<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.4
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
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
     * Get debug status.
     *
     * @param bool $global
     * @return bool
     */
    function isDebug($global = false);

    /**
     * Set logger path.
     *
     * @param string $path
     * @return void
     */
    function setPath($path);

    /**
     * Set logger filename.
     *
     * @param string $filename
     * @return void
     */
    function setFilename($filename);

    /**
     * Set logger extension.
     *
     * @param string $extension
     * @return void
     */
    function setExtension($extension);

    /**
     * Set logger debug.
     *
     * @param string $message
     * @param bool $isArray
     * @return void
     */
    function debug($message = '', $isArray = false);

    /**
     * Set logger error.
     *
     * @param string $message
     * @return void
     */
    function error($message = '');

    /**
     * Set logger warning.
     *
     * @param string $message
     * @return void
     */
    function warning($message = '');

    /**
     * Set logger info.
     *
     * @param string $message
     * @return void
     */
    function info($message = '');

    /**
     * Set logger custom message.
     *
     * @param string $message
     * @param string $type
     * @return void
     */
    function custom($message = '', $type = 'custom');

    /**
     * Log natif PHP errors.
     *
     * @param string $message
     * @param int $type 0
     * @param string $path
     * @param string $headers
     * @return void
     */
    function log($message = '', $type = 0, $path = null, $headers = null);
}
