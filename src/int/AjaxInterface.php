<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.2
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
     * Ajax init hook.
     *
     * @param AdminAjaxInterface $callable
     * @param PluginNameSpaceInterface $plugin
     *
     * Action: wp_ajax_{namespace}-{action} (admin)
     * Action: wp_ajax_nopriv_{namespace}-{action} (front)
     */
    function __construct(AdminAjaxInterface $callable, PluginNameSpaceInterface $plugin);

    /**
     * Ajax admin action callback.
     *
     * @param void
     * @return void
     */
    function adminCallback();

    /**
     * Ajax front action callback.
     *
     * @param void
     * @return void
     */
    function frontCallback();

    /**
     * Validate Ajax action,
     * Accept both POST & GET methods.
     *
     * @param string $action
     * @return bool
     */
    function isAction($action);
}
