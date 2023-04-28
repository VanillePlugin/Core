<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\exc;

class ConfigurationException extends VanillePluginException
{
	public static function invalidPluginConfigurationFile($file)
	{
		return "Couldn't find plugin configuration file: '{$file}'";
	}

    public static function invalidPluginConfigurationFormat($file)
    {
        return "Invalid plugin configuration JSON format: '{$file}'";
    }

    public static function invalidPluginConfiguration($error, $file)
    {
        return "Invalid plugin configuration: '{$error}' in '{$file}'";
    }
}
