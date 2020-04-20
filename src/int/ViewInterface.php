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

interface ViewInterface
{
    /**
     * Define custom callables
     *
     * @access public
     * @param array $callables
     * @return void
     */
    public function setCallables($callables = []);
    
    /**
     * Render view
     *
     * @access public
     * @param {inherit}
     * @return void
     */
    public function render($content = [], $template = 'default');

    /**
     * Aassign content to view
     *
     * @access public
     * @param array $content
     * @param string $template
     * @return string
     */
    public function assign($content = [], $template = 'default');
}
