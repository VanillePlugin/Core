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

namespace VanillePlugin\lib;

use VanillePlugin\int\AjaxInterface;

/**
 * Plugin AJAX manager.
 */
class Ajax implements AjaxInterface
{
	use \VanillePlugin\VanillePluginOption;

	/**
	 * @access private
	 * @var array $actions
	 * @var bool $isAdmin
	 */
	private $actions = [];
	private $isAdmin = false;

	/**
	 * @inheritdoc
	 */
	public final function __construct()
	{
		if (!$this->isAjax()) {
			return;
		}

		if ($this->isAdminCallable()) {
			$this->isAdmin = true;
			$this->actions = $this->getAdminAjax();
		} elseif ($this->isFrontCallable()) {
			$this->actions = $this->getFrontAjax();
		}
	}

	/**
	 * @inheritdoc
	 */
	public final function register()
	{
		foreach ($this->actions as $action) {
			$action = $this->applyNamespace($action);
			$this->addAction("wp-ajax-{$action}", [$this, 'callback']);
			if (!$this->isAdmin) {
				$this->addAction("wp-ajax-nopriv-{$action}", [$this, 'callback']);
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public final function callback()
	{
		foreach ($this->actions as $action) {

			if ($this->isAction($action)) {

				$this->verifyToken($action);
				if ($this->isAdmin) {
					$this->verifyPermission();
				}

				$action = $this->camelcase($action);
				$this->{$action}();
				break;
			}
		}

		die();
	}

	/**
	 * Validate Ajax action.
	 * [Methods: GET|POST].
	 *
	 * @access protected
	 * @param string $action
	 * @return bool
	 */
	protected function isAction(string $action): bool
	{
		$action = $this->applyNamespace($action);
		if ($this->getRequest('action') == $action) {
			return true;
		}
		return false;
	}

	/**
	 * Check request value in payload.
	 *
	 * @access protected
	 * @param string $item
	 * @return mixed
	 */
	protected function inPayload(string $item)
	{
		if (!($value = $this->getRequest($item))) {
			return false;
		}
		return $value;
	}

	/**
	 * Check whether Ajax callable is admin.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function isAdminCallable(): bool
	{
		return $this->hasObject('interface', $this, 'AdminAjax');
	}

	/**
	 * Check whether Ajax callable is front.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function isFrontCallable(): bool
	{
		return $this->hasObject('interface', $this, 'FrontAjax');
	}
}
