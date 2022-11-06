<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.2
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface UpdaterInterface
{
    /**
     * @param PluginNameSpaceInterface $plugin
     * @param string $host
     * @param array $args
     */
    function __construct(PluginNameSpaceInterface $plugin, $host, $args = []);

    /**
     * @param object $transient
     * @param string $action
     * @param array $args
     * @return mixed
     */
    function getInfo($transient, $action, $args);
    
    /**
     * @param object $transient
     * @return mixed
     */
    function checkUpdate($transient);

    /**
     * @param object $transient
     * @return mixed
     */
    function checkTranslation($transient);
}
