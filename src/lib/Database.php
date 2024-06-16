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

namespace VanillePlugin\lib;

/**
 * Database dynamic autoloader.
 */
class Database
{
	use \VanillePlugin\VanillePluginOption;

	/**
	 * Load database table.
	 *
	 * @access public
	 * @param string $name
	 * @param mixed $args
	 * @return mixed
	 */
	public function load(string $name, ...$args)
	{
		$path = $this->applyPluginFilter('database-path', 'db');
		return (new Loader())->i($path, $name, $args);
	}
}
