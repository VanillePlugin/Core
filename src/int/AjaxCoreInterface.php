<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface AjaxCoreInterface
{
    /**
     * Init plugin Ajax.
     *
     * @param AjaxInterface $callable
     */
    function __construct(AjaxInterface $callable);

    /**
     * Ajax action callback,
     * Uses isAction() to validate action.
     *
     * @return void
     */
    function callback();

	/**
	 * Validate Ajax action,
	 * Accept all HTTP methods.
	 *
	 * @param string $action
	 * @return bool
	 */
	function isAction(string $action) : bool;
}
