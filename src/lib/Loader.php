<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.5
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\File;

/**
 * Class loader for custom functions and shortcodes.
 * @internal
 */
final class Loader extends PluginOptions
{
	/**
	 * @access private
	 * @var string $regex
	 */
	private $regex = '/^.*\.(php)$/i';

    /**
     * @param PluginNameSpaceInterface $plugin
     */
    public function __construct(PluginNameSpaceInterface $plugin)
	{
        // Init plugin config
        $this->initConfig($plugin);
	}

	/**
	 * Instance class.
	 * 
	 * @access public
	 * @param string $path
	 * @param string $className
	 * @param mixed $arg1
	 * @param mixed $arg2
	 * @return mixed
	 */
	public function instance($path, $className, $arg1 = null, $arg2 = null)
	{
		$path = ltrim($path,'/');
		$path = ltrim($path,'\\');
		$dir = "{$this->getRoot()}/core/system/functions/{$path}";
		if ( File::isDir($dir) ) {
			$files = $this->scan($dir);
			$className = Stringify::lowercase($className);
			if ( isset($files[$className]) ) {
				if ( TypeCheck::isClass($files[$className]) ) {
					$class = $files[$className];
					return new $class($arg1,$arg2);
				}
			}
		}
		return false;
	}

	/**
	 * Instance alias.
	 * 
	 * @access public
	 * @param string $path
	 * @param string $className
	 * @param mixed $arg1
	 * @param mixed $arg2
	 * @return mixed
	 */
	public function i($path, $className, $arg1 = null, $arg2 = null)
	{
		return $this->instance($path,$className,$arg1,$arg2);
	}

	/**
	 * Set regex.
	 * 
	 * @access public
	 * @param string $regex
	 * @return void
	 */
	public function setRegex($regex)
	{
		$this->regex = $regex;
	}

	/**
	 * Scan classes files.
	 * 
	 * @access public
	 * @param string $path
	 * @return array
	 */
	private function scan($path)
	{
		$files = File::scanDir($path);
		$base = basename($path);
		$namespace = "{$this->getNameSpace()}\\core\\system\\functions\\{$base}";
		foreach ($files as $key => $name) {
			if ( Stringify::match($this->regex,$name) ) {
				$name = substr($name,0,strrpos($name,'.php'));
				$slug = Stringify::lowercase($name);
				$files[$slug] = "{$namespace}\\{$name}";
			}
			unset($files[$key]);
		}
		return $files;
	}
}
