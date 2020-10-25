<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.7
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
     * @param boolean $unsafe true
     * @return void
     */
    function __construct(PluginNameSpaceInterface $plugin, $host, $params = [], $unsafe = true);

    /**
     * @param object $transient
     * @return mixed
     */
    function check($transient);

    /**
     * @param object $transient
     * @param string $action
     * @param array $args
     * @return mixed
     */
    function infos($transient, $action, $args);

    /**
     * @param array $args
     * @return array
     */
    function setRequest($args);
}
