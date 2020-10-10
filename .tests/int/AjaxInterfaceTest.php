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

namespace VanillePluginTest\int;

interface AjaxInterfaceTest
{
    /**
     * Ajax Controller
     *
     * @param object $callable
     * @param PluginNameSpaceInterfaceTest $plugin
     * @return void
     */
    function __construct(AdminAjaxInterfaceTest $callable, PluginNameSpaceInterfaceTest $plugin);
    
    /**
     * @param void
     * @return void
     */
    function callback();

    /**
     * @param string $action
     * @return boolean
     */
    function isAction($action);
}
