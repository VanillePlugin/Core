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

interface ConfigInterface
{
    /**
     * @param void
     * @return void
     */
    function __construct($path = '');

    /**
     * @param void
     * @return void
     */
    static function getNameSpace();    

    /**
     * @param void
     * @return void
     */
    static function getRoot();
}
