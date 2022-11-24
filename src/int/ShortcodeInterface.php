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

namespace VanillePlugin\int;

interface ShortcodeInterface
{
    /**
     * Init shortcode(s).
     *
     * @param void
     * @return void
     */
    function init();

    /**
     * Do shortcode main callable.
     * 
     * @param array $atts
     * @param string $content
     * @param string $tag
     * @return string
     */
    function doCallable($atts = [], $content = null, $tag = '');
}
