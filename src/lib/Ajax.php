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

use VanillePlugin\inc\HttpRequest;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\int\AjaxCoreInterface;
use VanillePlugin\int\AjaxInterface;
use VanillePlugin\int\PluginNameSpaceInterface;

/**
 * Wrapper class for Ajax inside plugins (PluginNameSpaceInterface).
 * @see Front actions requires admin hook for authenticated users.
 */
final class Ajax extends PluginOptions implements AjaxCoreInterface
{
	/**
	 * @access private
	 * @var object $callable
	 * @var array $actions
	 */
	private $callable;
	private $actions = [];

	/**
	 * Init plugin Ajax.
	 *
	 * @param AjaxInterface $callable
	 * @param PluginNameSpaceInterface $plugin
	 */
	public function __construct(AjaxInterface $callable, PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);

		// Set callable
		$this->callable = $callable;

		// Init admin actions
		if ( $this->isAdminCallable() ) {
			$this->actions = $this->getAdminAjax();
			$this->initAdminActions();
		}

		// Init front actions
		if ( $this->isFrontCallable() ) {
			$this->actions = $this->getFrontAjax();
			$this->initFrontActions();
		}
	}

	/**
	 * Ajax action callback.
	 *
	 * @access public
	 * @param void
	 * @return void
	 * @see Use: 'isAction()' to validate action
	 */
	public function callback()
	{
		foreach ($this->actions as $action) {
			if ( $this->isAction($action) ) {
				$this->callable->{$action}();
			}
		}
		die();
	}

	/**
	 * Validate Ajax action,
	 * Accept both POST & GET methods.
	 *
	 * @access private
	 * @param string $action
	 * @return bool
	 */
	private function isAction($action)
	{
		if ( HttpRequest::isSetted('action') ) {
			if ( HttpRequest::get('action') == "{$this->getNameSpace()}-{$action}" ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check whether Ajax callable is admin.
	 *
	 * @access private
	 * @param void
	 * @return bool
	 */
	private function isAdminCallable()
	{
		$interface = 'VanillePlugin\int\AdminAjaxInterface';
		return TypeCheck::hasInterface($this->callable,$interface);
	}

	/**
	 * Check whether Ajax callable is front.
	 *
	 * @access private
	 * @param void
	 * @return bool
	 */
	private function isFrontCallable()
	{
		$interface = 'VanillePlugin\int\FrontAjaxInterface';
		return TypeCheck::hasInterface($this->callable,$interface);
	}

	/**
	 * Init admin actions.
	 * Action: wp_ajax_{namespace}-{action}
	 *
	 * @access private
	 * @param void
	 * @return bool
	 */
	private function initAdminActions()
	{
		foreach ($this->actions as $action) {
			$this->addAction(
				"wp_ajax_{$this->getNameSpace()}-{$action}",
				[$this,'callback']
			);
		}
	}

	/**
	 * Init front actions.
	 * Action: wp_ajax_nopriv_{namespace}-{action}
	 *
	 * @access private
	 * @param void
	 * @return bool
	 */
	private function initFrontActions()
	{
		foreach ($this->actions as $action) {
			$this->addAction(
				"wp_ajax_nopriv_{$this->getNameSpace()}-{$action}",
				[$this,'callback']
			);
		}
	}
}
