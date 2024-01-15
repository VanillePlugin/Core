<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin;

use VanillePlugin\inc\TypeCheck;
use VanillePlugin\exc\ConfigurationException;
use JsonSchema\Validator;

final class VanillePluginValidator
{
	/**
	 * Check configuration object.
	 * 
	 * @access public
	 * @var mixed $global,
	 * @var string $file
	 * @return void
	 * @throws ConfigurationException
	 */
	public static function checkConfig($global, ?string $file = null)
	{
		$error = self::isValidConfig($global);
		if ( TypeCheck::isString($error) ) {
	        throw new ConfigurationException(
	            ConfigurationException::invalidPluginConfiguration($error, $file)
	        );

		} elseif ( $error === false ) {
	        throw new ConfigurationException(
	            ConfigurationException::invalidPluginConfigurationFormat($file)
	        );
		}
	}

	/**
	 * Validate plugin configuration.
	 * 
	 * @access private
	 * @var mixed $config
	 * @return mixed
	 */
	private static function isValidConfig($config)
	{
		if ( TypeCheck::isObject($config) ) {
			$validator = new Validator();
			$validator->validate($config, (object)[
				'$ref' => 'file://' . __DIR__ . '/bin/config.schema.json'
			]);
			if ( $validator->isValid() ) {
				return true;

			} else {
				$errors = [];
			    foreach ($validator->getErrors() as $error) {
			        $errors[] = sprintf("[%s] %s", $error['property'], $error['message']);
			    }
			    return implode("\n", $errors);
			}
		}
		return false;
	}
}
