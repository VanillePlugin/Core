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

namespace VanillePlugin;

use VanillePlugin\exc\ConfigException;
use VanillePlugin\inc\{
	TypeCheck,
	Stringify,
	Arrayify
};
use JsonSchema\Validator;

/**
 * Configuration validator.
 */
final class VanillePluginValidator
{
	/**
	 * Validate config data using schema.
	 *
	 * @access public
	 * @var mixed $data
	 * @var string $schema
	 * @return void
	 * @throws ConfigException
	 */
	public static function validate($data, string $schema)
	{
		if (!self::isValid($data, $schema, $error)) {

			if (TypeCheck::isString($error)) {
				throw new ConfigException(
					ConfigException::invalidConfig($error, $schema)
				);
			}

			throw new ConfigException(
				ConfigException::invalidConfigFormat($schema)
			);
		}
	}

	/**
	 * Check whether config has valid schema.
	 *
	 * @access private
	 * @var mixed $data
	 * @var string $schema
	 * @var string $error
	 * @return bool
	 */
	private static function isValid($data, string $schema, &$error = null): bool
	{
		$validator = new Validator();
		$path = Stringify::formatPath(__DIR__ . "/bin/{$schema}.schema.json");
		$validator->validate($data, (object)[
			'$ref' => "file://{$path}"
		]);

		if (!$validator->isValid()) {
			$errors = $validator->getErrors();
			$error  = Arrayify::shift($errors);
			$error  = "{$error['message']} [{$error['property']}]";
			return false;
		}

		return true;
	}
}
