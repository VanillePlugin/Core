<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
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
	 * Set custom view callables.
	 *
     * @param array $callables
	 * @return void
	 */
	function setCallables(array $callables = []);
    
    /**
     * Render view.
     *
     * @param string $tpl
     * @param array $content
     * @param bool $end
     * @return void
     */
    function render(string $tpl = 'default', array $content = [], bool $end = false);

    /**
     * Aassign content to view.
     *
     * @param string $tpl
     * @param array $content
     * @return mixed
     */
    function assign(string $tpl = 'default', array $content = []);
}
