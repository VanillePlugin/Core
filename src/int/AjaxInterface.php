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

namespace VanillePlugin\int;

interface AjaxInterface
{
    /**
     * Init Ajax actions.
     * [Action: admin-init].
     */
	function __construct();

    /**
     * Register Ajax actions.
	 * [Action: wp-ajax-nopriv-{plugin}-{action}].
	 * [Action: wp-ajax-{plugin}-{action}].
     *
     * @return void
     */
	function register();

    /**
     * Ajax action callback.
     * [Uses: isAction()].
     *
     * @return void
     */
	function callback();
}
