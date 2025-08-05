<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\exc;

class ModelException extends VanillePluginException
{
    public static function undefinedTable(): string
    {
        return 'Undefined table name';
    }

    public static function invalidInstance(): string
    {
        return 'Invalid db instance';
    }
}
