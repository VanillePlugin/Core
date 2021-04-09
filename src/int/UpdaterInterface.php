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

namespace VanillePlugin\int;

interface UpdaterInterface
{
    /**
     * @param PluginNameSpaceInterface $plugin
     * @param string $host
     * @param array $params
     * @return void
     */
    function __construct(PluginNameSpaceInterface $plugin, $host, $params = []);

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
