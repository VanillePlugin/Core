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

namespace VanillePlugin\int;

interface AjaxInterface
{
    /**
     * Ajax Controller.
     *
     * @param object $callable
     * @param PluginNameSpaceInterface $plugin
     */
    function __construct(AdminAjaxInterface $callable, PluginNameSpaceInterface $plugin);
    
    /**
     * @param void
     * @return void
     */
    function callback();

    /**
     * @param string $action
     * @return bool
     */
    function isAction($action);
}
