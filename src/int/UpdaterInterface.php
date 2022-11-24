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

interface UpdaterInterface
{

    /**
     * @param PluginNameSpaceInterface $plugin
     * @param string $host
     * @param array $args
     *
     * Action: admin_init
     */
    function __construct(PluginNameSpaceInterface $plugin, $host, $args = []);

    /**
     * Get plugin info.
     * 
     * @param object $transient
     * @param string $action
     * @param object $args
     * @return mixed
     */
    function getInfo($transient, $action, $args);

    /**
     * Check plugin update.
     * 
     * @param object $transient
     * @return object
     */
    function checkUpdate($transient);

    /**
     * Check plugin translation update.
     * 
     * @param object $transient
     * @return object
     */
    function checkTranslation($transient);

    /**
     * Filter updater args,
     * Allow unsafe updater URLs for non SSL.
     * 
     * @param array $args
     * @return array
     */
    function filterArgs($args);
}
