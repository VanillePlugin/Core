<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\Template;

trait TraitViewable
{
    /**
     * Get view environment.
     * 
     * @access protected
     * @inheritdoc
     */
    protected function getEnvironment(string $path, array $options = []) : object
    {
        return Template::getEnvironment($path, $options);
    }

    /**
     * Add view callable.
     * 
     * @access protected
     * @inheritdoc
     */
    protected function extend(string $name, $callable = null, array $options = []) : object
    {
        return Template::extend($name, $callable, $options);
    }
}
