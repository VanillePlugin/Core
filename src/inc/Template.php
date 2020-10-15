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

namespace VanillePlugin\inc;

use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Environment;
use Twig_SimpleFunction as WPFunction;

class Template
{
    /**
     * @param string $path
     * @param array $settings
     * @return object
     */
    public static function getEnvironment($path, $settings = [])
    {
        return new Environment(new Loader($path), $settings);
    }

    /**
     * @param string $name
     * @param callable $function
     * @return object
     */
    public static function extend($name, $function)
    {
        return new WPFunction($name, $function);
    }
}
