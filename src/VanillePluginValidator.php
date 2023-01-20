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

namespace VanillePlugin;

use VanillePlugin\inc\Validator;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\inc\File;
use VanillePlugin\inc\GlobalConst;
use VanillePlugin\exc\NamepsaceException;
use VanillePlugin\exc\ConfigurationException;
use JsonSchema\Validator as JsonValidator;

final class VanillePluginValidator extends Validator
{
	/**
	 * @access public
	 * @var mixed $plugin
	 * @return void
	 * @throws NamepsaceException
	 */
	public static function checkNamespace($plugin)
	{
		if ( !self::isValidNamespace($plugin) ) {
			$namespace = $plugin->getNameSpace();
			if ( TypeCheck::isObject($namespace) ) {
				$namespace = '{Object}';
			} elseif ( TypeCheck::isArray($namespace) ) {
				$namespace = '[Array]';
			} else {
				$namespace = (string)$namespace;
			}
	        throw new NamepsaceException(
	            NamepsaceException::invalidPluginNamepsace($namespace)
	        );
		}
	}

	/**
	 * @access public
	 * @var object $global,
	 * @var string $file
	 * @return void
	 * @throws ConfigurationException
	 */
	public static function checkConfig($global, $file = null)
	{
		$error = self::isValidConfig($global);
		if ( TypeCheck::isString($error) ) {
	        throw new ConfigurationException(
	            ConfigurationException::invalidPluginConfiguration($error,$file)
	        );
		} elseif ( $error === false ) {
	        throw new ConfigurationException(
	            ConfigurationException::invalidPluginConfigurationFormat($file)
	        );
		}
	}

	/**
	 * Validate plugin namespace.
	 * 
	 * @access private
	 * @var object $plugin
	 * @return bool
	 */
	private static function isValidNamespace($plugin)
	{
		if ( TypeCheck::isObject($plugin) ) {
			if ( TypeCheck::isString($plugin->getNameSpace()) ) {
				if ( !empty($namespace = $plugin->getNameSpace()) ) {
					return File::exists(
						GlobalConst::pluginDir("/{$namespace}/{$namespace}.php")
					);
				}
			}
		}
		return false;
	}

	/**
	 * Validate plugin configuration.
	 * 
	 * @access private
	 * @var object $config
	 * @return mixed
	 */
	private static function isValidConfig($config)
	{
		if ( TypeCheck::isObject($config) ) {
			$validator = new JsonValidator;
			$validator->validate($config, (object)[
				'$ref' => 'file://' . dirname(__FILE__).'/config.schema.json'
			]);
			if ( $validator->isValid() ) {
				return true;
			} else {
				$errors = [];
			    foreach ($validator->getErrors() as $error) {
			        $errors[] = sprintf("[%s] %s",$error['property'],$error['message']);
			    }
			    return implode("\n", $errors);
			}
		}
		return false;
	}
}
