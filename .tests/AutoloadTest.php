<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.6
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePluginTest;

final class AutoloadTest
{
	/**
	 * Register Plugin Autoloader
	 *
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		// WordPress Security Basic
		if (!defined('ABSPATH')) die('forbidden');

		// Include Composer Dependencies
		require_once( __DIR__ . '/vendor/autoload.php');
		
		// Init Self Autoloader
		$this->register();
	}

	/**
	 * Register autoloader
	 * MUST be before any include
	 *
	 * @access protected
	 * @param void
	 * @return void
	 */
	protected function register()
	{
	    spl_autoload_register([__CLASS__, 'autoload']);
	}

	/**
	 * Unregister autoloader
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function unregister()
	{
		spl_autoload_unregister([__CLASS__, 'autoload']);
	}

	/**
	 * Autoloader method
	 * 
	 * @access protected
	 * @param string $class __CLASS__
	 * @return void
	 */
	protected function autoload($class)
	{
	    if ( strpos($class, __NAMESPACE__ . '\\') === 0 ) {
	        $class = str_replace(__NAMESPACE__ . '\\', '', $class);
	        $class = str_replace('\\', '/', $class);
	        $namespace = str_replace('\\', '/', __NAMESPACE__);
	        $namespace = str_replace('_', '-', __NAMESPACE__);
	        require_once( __DIR__ . "/{$namespace}/{$class}.php" );
	    }
	}

	/**
	 * @access public
	 * @param void
	 * @return object
	 */
	public static function init()
	{
		return new self;
	}
}
