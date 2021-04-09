<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.8
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use Twig\Loader\FilesystemLoader as Loader;
use Twig\Environment as Environment;
use Twig\TwigFunction as WPFunction;

final class Template
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
