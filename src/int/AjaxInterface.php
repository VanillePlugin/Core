<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.3
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\int;

interface AjaxInterface
{
    /**
     * Ajax Controller
     *
     * @param object $callable
     * @param PluginNameSpaceInterface $plugin
     * @return void
     */
    function __construct(AdminAjaxInterface $callable, PluginNameSpaceInterface $plugin);
    
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
