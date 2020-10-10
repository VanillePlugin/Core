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

interface AdminInterfaceTest
{
    /**
     * @param MenuInterface $menu
     * @param SettingsInterface $settings
     * @return void
     */
	function __construct(MenuInterfaceTest $menu = null, SettingsInterfaceTest $settings = null);
}
