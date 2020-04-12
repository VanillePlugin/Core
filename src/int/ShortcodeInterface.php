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

interface ShortcodeInterface
{
    /**
     * @param void
     * @return void
     */
	function __construct();
	
    /**
     * @param string $name
     * @param array $callable
     * @return void
     */
	public function add($name, $callable = []);
}
