<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.5
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin;

use VanillePlugin\inc\Validator;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\inc\Exception;
use VanillePlugin\inc\File;
use VanillePlugin\inc\GlobalConst;
use JsonSchema\Validator as JsonValidator;

final class VanillePluginValidator extends Validator
{
	/**
	 * @access public
	 * @var mixed $plugin
	 * @return void
	 */
	public static function checkNamespace($plugin)
	{
		try {
			if ( !self::isValidNamespace($plugin) ) {
				$namespace = $plugin->getNameSpace();
				if ( TypeCheck::isObject($namespace) ) {
					$namespace = '{Object}';
				} elseif ( TypeCheck::isArray($namespace) ) {
					$namespace = '[Array]';
				} else {
					$namespace = (string)$namespace;
				}
				throw new VanillePluginException($namespace);
			}
		} catch (VanillePluginException $e) {
			die($e->get(1));
		}
	}

	/**
	 * @access public
	 * @var mixed $json
	 * @return void
	 */
	public static function checkConfig($json)
	{
		try {
			$error = self::isValidConfig($json);
			if ( TypeCheck::isString($error) ) {
				throw new VanillePluginException($error);
			} elseif ( $error === false ) {
				throw new VanillePluginException();
			}
		} catch (VanillePluginException $e) {
			die($e->get(2));
		}
	}

	/**
	 * @access private
	 * @var mixed $plugin
	 * @return bool
	 */
	private static function isValidNamespace($plugin)
	{
		if ( TypeCheck::isObject($plugin) ) {
			if ( TypeCheck::isString($plugin->getNameSpace()) ) {
				if ( !empty($namespace = $plugin->getNameSpace()) ) {
					return File::exists(GlobalConst::pluginDir("/{$namespace}/{$namespace}.php"));
				}
			}
		}
		return false;
	}

	/**
	 * @access private
	 * @var mixed $config
	 * @return mixed
	 */
	private static function isValidConfig($config)
	{
		if ( $config->parse() && !empty($config->parse()) ) {
			$validator = new JsonValidator;
			$json = $config->parse();
			$validator->validate($json, (object)[
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
