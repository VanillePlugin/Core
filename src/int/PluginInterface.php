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
	public static function start();

    /**
     * @param void
     * @return void
     */
	public function activate();

    /**
     * @param void
     * @return void
     */
	public function deactivate();

    /**
     * @param void
     * @return void
     */
	public static function uninstall();
}
