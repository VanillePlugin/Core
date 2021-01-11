<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.4
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\int;

interface ViewInterface
{
    /**
     * Define custom callables
     *
     * @access public
     * @param array $callables
     * @return void
     */
    function setCallables($callables = []);
    
    /**
     * Render view
     *
     * @access public
     * @param {inherit}
     * @return void
     */
    function render($content = [], $template = 'default');

    /**
     * Aassign content to view
     *
     * @access public
     * @param array $content
     * @param string $template
     * @return string
     */
    function assign($content = [], $template = 'default');
}
