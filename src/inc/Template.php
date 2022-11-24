<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.3
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

use Twig\Loader\FilesystemLoader as Loader;
use Twig\Environment as Environment;
use Twig\TwigFunction as Module;

/**
 * Wrapper class for Twig.
 */
final class Template
{
    /**
     * @param string $path
     * @param array $settings
     * @return object
     */
    public static function getEnvironment($path, $settings = [])
    {
        return new Environment(new Loader($path),$settings);
    }

    /**
     * @param string $name
     * @param callable $function
     * @return object
     */
    public static function extend($name, $function)
    {
        return new Module($name,$function);
    }
}
