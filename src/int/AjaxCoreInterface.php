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

namespace VanillePlugin\int;

interface AjaxCoreInterface
{
    /**
     * Init plugin Ajax.
     *
     * @param AjaxInterface $callable
     * @param PluginNameSpaceInterface $plugin
     */
    function __construct(AjaxInterface $callable, PluginNameSpaceInterface $plugin);

    /**
     * Ajax action callback.
     *
     * @param void
     * @return void
     */
    function callback();
}
