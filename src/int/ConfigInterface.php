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

interface ConfigInterface
{
    /**
     * @param void
     * @return void
     */
    function __construct();
    
    /**
     * @param string $property
     * @return void
     */
	public function __get($property);

    /**
     * @param string $property
     * @param string $value
     * @return void
     */
	public function __set($property,$value);
}
