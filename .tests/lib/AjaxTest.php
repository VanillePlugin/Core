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

namespace VanillePluginTest\lib;

use VanillePluginTest\lib\PluginOptionsTest;
use VanillePluginTest\inc\PostTest;
use VanillePluginTest\inc\GetTest;
use VanillePluginTest\int\AjaxInterfaceTest;
use VanillePluginTest\int\AdminAjaxInterfaceTest;
use VanillePluginTest\int\PluginNameSpaceInterfaceTest;

class AjaxTest extends PluginOptionsTest implements AjaxInterfaceTest
{
	/**
	 * @access private
	 * @var array $callable
	 * @var array $actions
	 */
	private $callable = [];
	private $actions = [];

	/**
	 * Ajax Controller
	 *
	 * @param object $callable
	 * @param PluginNameSpaceInterfaceTest $plugin
	 * @return void
	 *
	 * action : wp_ajax_{namespace}-{action}
	 * action : wp_ajax_nopriv_{namespace}-{action}
	 */
	public function __construct(AdminAjaxInterfaceTest $callable, PluginNameSpaceInterfaceTest $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);

		// Set vars
		$this->callable = $callable;
		$this->actions = $this->getAjax();

		// Add hooks
		foreach ($this->actions as $action) {
			$this->addAction("wp_ajax_{$this->getNameSpace()}-{$action}", [$this,'callback']);
			$this->addAction("wp_ajax_nopriv_{$this->getNameSpace()}-{$action}", [$this,'callback']);
		}
	}

	/**
	 * AjaxCallback react as Ajax Controller
	 *
	 * @access public
	 * @param void
	 * @return void
	 *
	 * use : isAction to separate actions
	 */
	public function callback()
	{
		foreach ($this->actions as $action) {
			while ( $this->isAction($action) ) {
				$this->callable->$action();
				exit();
			}
		}
		die();
	}

	/**
	 * Check required action
	 * Supports both Post & Get method
	 *
	 * @access public
	 * @param string $action
	 * @return boolean
	 */
	public function isAction($action)
	{
		if ( PostTest::isSetted('action') ) {
			if (PostTest::get('action') == "{$this->getNameSpace()}-{$action}") {
				return true;
			}
		} elseif ( GetTest::isSetted('action') ) {
			if (GetTest::get('action') == "{$this->getNameSpace()}-{$action}") {
				return true;
			}
		}
		return false;
	}
}
