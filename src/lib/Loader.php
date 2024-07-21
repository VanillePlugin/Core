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
 * Plugin functions loader.
 */
class Loader
{
	use \VanillePlugin\VanillePluginConfig;

	protected const BASEDIR = 'core/system/functions';
	protected const PATTERN = '/^.*\.(php)$/i';

	/**
	 * @access protected
	 * @var string $baseDir
	 * @var string $pattern
	 */
	protected $baseDir;
	protected $pattern;

	/**
	 * Init loader.
	 */
	public function __construct(string $baseDir = self::BASEDIR, string $pattern = self::PATTERN)
	{
		$this->baseDir = $this->format($baseDir);
		$this->pattern = $pattern;
	}

	/**
	 * Instance class.
	 *
	 * @access public
	 * @param string $path
	 * @param string $className
	 * @param mixed $args
	 * @return mixed
	 */
	public function instance($path, $className, ...$args)
	{
		$path = $this->format($path);
		$dir = "{$this->getRoot()}/{$this->baseDir}/{$path}";
		if ( $this->isDir($dir) ) {
			$files = $this->scan($dir, $path);
			$className = $this->lowercase($className);
			if ( isset($files[$className]) ) {
				if ( $this->isType('class', $files[$className]) ) {
					$class = $files[$className];
					return new $class(...$args);
				}
			}
		}
		return false;
	}

	/**
	 * Instance class (Alias).
	 *
	 * @access public
	 * @param string $path
	 * @param string $className
	 * @param mixed $args
	 * @return mixed
	 */
	public final function i($path, $className, ...$args)
	{
		return $this->instance($path, $className, ...$args);
	}

	/**
	 * Scan classes files.
	 *
	 * @access protected
	 * @param string $dir
	 * @param string $base
	 * @return array
	 */
	protected function scan(string $dir, string $base)
	{
		$files = $this->scanDir($dir);
		$namespace = $this->format("{$this->baseDir}/{$base}", true);
		foreach ($files as $key => $name) {
			if ( $this->matchString($this->pattern, $name) ) {
				$name = substr($name, 0, strrpos($name, '.php'));
				$slug = $this->lowercase($name);
				$files[$slug] = "{$namespace}\\{$name}";
			}
			unset($files[$key]);
		}
		return $files;
	}

	/**
	 * Format loader path.
	 *
	 * @access protected
	 * @param string $path
	 * @param bool $namespace
	 * @return string
	 */
	protected function format(string $path, bool $namespace = false)
	{
        $path = ltrim($path, '/');
        $path = rtrim($path, '/');
        $path = ltrim($path, '\\');
        $path = rtrim($path, '\\');
        if ( $namespace ) {
        	$path = $this->replaceString('/', '\\', $path);
			$path = $this->applyNameSpace($path, '\\');
        }
        return $path;
	}
}
