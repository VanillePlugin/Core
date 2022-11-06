<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.1
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\HttpRequest;
use VanillePlugin\int\AjaxInterface;
use VanillePlugin\int\AdminAjaxInterface;
use VanillePlugin\int\PluginNameSpaceInterface;

class Ajax extends PluginOptions implements AjaxInterface
{
	/**
	 * @access private
	 * @var object $callable
	 * @var object $actions
	 * @var string $type
	 */
	private $callable;
	private $actions;

	/**
	 * Ajax init hook.
	 *
	 * @param AdminAjaxInterface $callable
	 * @param PluginNameSpaceInterface $plugin
	 *
	 * Action: wp_ajax_{namespace}-{action} (admin)
	 * Action: wp_ajax_nopriv_{namespace}-{action} (front)
	 */
	public function __construct(AdminAjaxInterface $callable, PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);

		// Set vars
		$this->callable = $callable;
		$this->actions = $this->getAjax();

		// Add admin hook
		foreach ($this->actions->admin as $action) {
			$this->addAction("wp_ajax_{$this->getNameSpace()}-{$action}", [$this,'adminCallback']);
		}

		// Add front hook
		foreach ($this->actions->front as $action) {
			$this->addAction("wp_ajax_nopriv_{$this->getNameSpace()}-{$action}", [$this,'frontCallback']);
		}
	}

	/**
	 * Ajax admin action callback.
	 *
	 * @access public
	 * @param void
	 * @return void
	 * @see Use 'isAction' to validate action
	 */
	public function adminCallback()
	{
		foreach ($this->actions->admin as $action) {
			if ( $this->isAction($action) ) {
				$this->callable->{$action}();
			}
		}
		die();
	}

	/**
	 * Ajax front action callback.
	 *
	 * @access public
	 * @param void
	 * @return void
	 * @see Use 'isAction' to validate action
	 */
	public function frontCallback()
	{
		foreach ($this->actions->front as $action) {
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
	 * @access public
	 * @param string $action
	 * @return bool
	 */
	public function isAction($action)
	{
		if ( HttpRequest::isSetted('action') ) {
			if ( HttpRequest::get('action') == "{$this->getNameSpace()}-{$action}" ) {
				return true;
			}
		}
		return false;
	}
}
