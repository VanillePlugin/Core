<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

namespace VanillePlugin\int;

interface AdminInterface
{
    /**
     * @param MenuInterface $menu
     * @param SettingsInterface $settings
     */
	function __construct(MenuInterface $menu = null, SettingsInterface $settings = null);
}
