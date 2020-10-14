<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.8
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\int;

interface PluginInterface
{
    /**
     * @param void
     * @return void
     */
    function __construct();
    
    /**
     * @param void
     * @return void
     */
	static function start();

    /**
     * @param void
     * @return void
     */
	function activate();

    /**
     * @param void
     * @return void
     */
	function deactivate();

    /**
     * @param void
     * @return void
     */
	static function uninstall();
}
