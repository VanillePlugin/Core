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

namespace VanillePlugin\exc;

class ConfigException extends VanillePluginException
{
	public static function invalidConfigFile(string $file) : string
	{
		return "Couldn't find plugin configuration file: '{$file}'";
	}

    public static function invalidConfigFormat(string $schema) : string
    {
        return "Invalid plugin configuration JSON format: '{$schema}'";
    }

    public static function invalidConfig(string $error, string $schema) : string
    {
        return "Invalid plugin configuration: '{$error}' in '{$schema}'";
    }
}
