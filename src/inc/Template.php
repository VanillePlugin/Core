<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
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
 * Wrapper class for Twig template engine.
 * @see https://twig.symfony.com
 */
final class Template
{
    /**
     * Get view environment.
     * Used single path for security.
     *
     * @access public
     * @param string $path
     * @param array $options
     * @return object
     */
    public static function getEnvironment(string $path, array $options = []): Environment
    {
        return new Environment(new Loader($path), $options);
    }

    /**
     * Add view callable.
     *
     * @access public
     * @param string $name
     * @param callable $callable
     * @param array $options
     * @return object
     */
    public static function extend(string $name, $callable = null, array $options = []): Module
    {
        return new Module($name, $callable, $options);
    }
}
