<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 * Allowed to edit for plugin customization
 */

namespace VanillePlugin\int;

interface AdminInterface
{
    /**
     * @param ConfigInterface $config
     * @param MenuInterface $menu
     * @param SettingsInterface $settings
     * @return void
     */
	function __construct(ConfigInterface $config = null, MenuInterface $menu = null, SettingsInterface $settings = null);
}
