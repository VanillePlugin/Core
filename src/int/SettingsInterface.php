<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.4
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
    function setDefault();

    /**
     * @param void
     * @return void
     */
    function remove();
}
