<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.3
 * @copyright : (c) 2018 - 2023 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface PluginInterface
{
    /**
     * Setup plugin.
     *
     * @param void
     */
    function __construct();

    /**
     * Prevent object clone.
     *
     * @param void
     * @return void
     */
    function __clone();

    /**
     * Prevent object unserialize.
     *
     * @param void
     * @return void
     */
    function __wakeup();

    /**
     * Plugin start action.
     *
     * @param void
     * @return void
     */
    static function start();

    /**
     * Plugin load action.
     *
     * @param void
     * @return void
     */
    function load();

    /**
     * Plugin activation action.
     *
     * @param void
     * @return void
     */
    function activate();

    /**
     * Plugin deactivation action.
     *
     * @param void
     * @return void
     */
    function deactivate();

    /**
     * Plugin upgrade action.
     *
     * @param object $upgrader
     * @param array $options
     * @return void
     */
    function upgrade($upgrader, $options);

    /**
     * Plugin uninstall action.
     *
     * @param void
     * @return void
     */
    static function uninstall();

    /**
     * Add plugin action links.
     *
     * @param array $links
     * @return array
     */
    function action($links);
}
