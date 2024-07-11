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

use VanillePlugin\int\{
	AjaxCoreInterface, AjaxInterface
};

/**
 * Plugin AJAX manager.
 */
final class Ajax implements AjaxCoreInterface
{
	use \VanillePlugin\VanillePluginOption;

	/**
	 * @access private
	 * @var object $callable
	 * @var array $actions
	 */
	private AjaxInterface $callable;
	private $actions = [];

	/**
	 * @inheritdoc
	 */
	public function __construct(AjaxInterface $callable)
	{
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
	 * @inheritdoc
	 */
	public function callback()
	{
		foreach ($this->actions as $action) {
			if ( $this->isAction($action) ) {

				$this->verifyToken($action);
				if ( $this->isAdminCallable() ) {
					$this->verifyPermission();
				}

				$action = $this->camelcase($action);
				$this->callable->{$action}();
				break;
			}
		}
		die();
	}

	/**
	 * @inheritdoc
	 */
	public function isAction(string $action) : bool
	{
		if ( $this->hasRequest('action') ) {
			if ( $this->getRequest('action') == $this->applyNamespace($action) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check whether Ajax callable is admin.
	 *
	 * @access private
	 * @return bool
	 */
	private function isAdminCallable() : bool
	{
		return $this->hasObject('interface', $this->callable, 'AdminAjaxInterface');
	}

	/**
	 * Check whether Ajax callable is front.
	 *
	 * @access private
	 * @return bool
	 */
	private function isFrontCallable() : bool
	{
		return $this->hasObject('interface', $this->callable, 'FrontAjaxInterface');
	}

	/**
	 * Init admin actions.
	 * [Action: wp-ajax-{plugin}-{action}].
	 *
	 * @access private
	 * @return void
	 */
	private function initAdminActions()
	{
		foreach ($this->actions as $action) {
			$this->addAction(
				"wp-ajax-{$this->applyNamespace($action)}",
				[$this, 'callback']
			);
		}
	}

	/**
	 * Init front actions.
	 * [Action: wp-ajax-nopriv-{plugin}-{action}].
	 *
	 * @access private
	 * @return void
	 */
	private function initFrontActions()
	{
		foreach ($this->actions as $action) {
			$this->addAction(
				"wp-ajax-nopriv-{$this->applyNamespace($action)}",
				[$this, 'callback']
			);
		}
	}
}
