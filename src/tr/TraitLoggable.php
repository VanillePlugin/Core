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

use VanillePlugin\lib\Logger;

/**
 * Define logging functions.
 */
trait TraitLoggable
{
    /**
     * Log debug message.
     *
     * @access protected
     * @inheritdoc
     */
    protected function debug($message, bool $isArray = false) : bool
    {
        return (new Logger())->debug($message, $isArray);
    }

    /**
     * Log error message.
     *
     * @access protected
     * @inheritdoc
     */
    protected function error(string $message) : bool
    {
        return (new Logger())->error($message);
    }

    /**
     * Log warning message.
     *
     * @access protected
     * @inheritdoc
     */
    protected function warning(string $message) : bool
    {
        return (new Logger())->warning($message);
    }
}
