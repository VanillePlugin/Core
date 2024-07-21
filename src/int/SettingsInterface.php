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

interface SettingsInterface
{
	/**
	 * Init plugin settings.
	 * [Action: admin-init].
	 *
	 * Template usage: {{ settingsFields('group') }}.
	 * Template usage: {{ settingsSections('group') }}.
	 * Template usage: {{ getOption('name') }}.
     *
	 * @return void
	 */
	function init();

	/**
	 * Define default settings.
	 *
	 * @return void
	 */
	function define();

    /**
     * Remove plugin settings.
     * [Action: uninstall-{plugin}].
     *
     * @return void
     */
    static function remove();
}
