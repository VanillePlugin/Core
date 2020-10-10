<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.6
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePluginTest\int;

interface OrmInterfaceTest
{
    /**
     * @param ConfigInterfaceTest $config
     * @param ShortcodeInterfaceTest $menu
     * @param SettingsInterfaceTest $settings
     * @return void
     */
	function select(OrmQueryInterfaceTest $data);

    /**
     * @param ConfigInterfaceTest $config
     * @param ShortcodeInterfaceTest $menu
     * @param SettingsInterfaceTest $settings
     * @return void
     */
	function count(OrmQueryInterfaceTest $data);

    /**
     * @param ConfigInterfaceTest $config
     * @param ShortcodeInterfaceTest $menu
     * @param SettingsInterfaceTest $settings
     * @return void
     */
	function insert($table, $data = [], $format = false);

    /**
     * @param ConfigInterfaceTest $config
     * @param ShortcodeInterfaceTest $menu
     * @param SettingsInterfaceTest $settings
     * @return void
     */
	function delete($table, $where = [], $format = null);

    /**
     * @param ConfigInterfaceTest $config
     * @param ShortcodeInterfaceTest $menu
     * @param SettingsInterfaceTest $settings
     * @return void
     */
	function deleteAll($table);
}
