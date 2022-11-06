<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.2
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin;

use VanillePlugin\inc\Exception;

class VanillePluginException extends Exception
{
	public static function invalidPluginNamepsace($namepsace = 'Empty')
	{
		return "Invalid Plugin Namepsace '{$namepsace}'";
	}

	public static function InvalidPluginConfiguration($config = 'Unknown')
	{
		return "Invalid Plugin Configuration '{$config}'";
	}
}
