<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface ViewInterface
{
    /**
     * Define custom callables.
     *
     * @param array $callables
     * @return void
     */
    function setCallables($callables = []);
    
    /**
     * Render view.
     *
     * @param array $content
     * @param string $template
     * @return void
     */
    function render($content = [], $template = 'default');

    /**
     * Aassign content to view.
     *
     * @param array $content
     * @param string $template
     * @return mixed
     */
    function assign($content = [], $template = 'default');
}
