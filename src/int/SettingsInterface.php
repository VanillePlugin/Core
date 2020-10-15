<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.9
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\int;

interface SettingsInterface
{
    /**
     * @param void
     * @return void
     */
	function init();

    /**
     * @param void
     * @return void
     */
    static function setDefault();

    /**
     * @param void
     * @return void
     */
    static function remove();
}
