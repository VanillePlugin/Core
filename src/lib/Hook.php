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

namespace VanillePlugin\lib;

use VanillePlugin\int\PluginNameSpaceInterface;

/**
 * Helper class for extra admin hooks (beta),
 * (Non multilingual).
 */
final class Hook extends PluginOptions
{
	/**
	 * @access public
	 * @var string FILTER
	 * @var string OPTION
	 */
	const FILTER = 'admin-extras';
	const OPTION = 'extras';

	/**
	 * Get valid hooks.
	 *
	 * @access public
	 * @param PluginNameSpaceInterface $plugin
	 * @return mixed
	 */
	public static function get(PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$hook = parent::getStatic();
        $hook->initConfig($plugin);

		// Filter settings
		if ( $hook->hasPluginFilter(static::FILTER) ) {

			$default = $hook->getPluginOption(static::OPTION,'array',[],false);
			$extras  = $hook->applyPluginFilter(static::FILTER,$default);

			// Reset settings
			foreach ($extras as $type => $extra) {
				foreach ($extra as $key => $value) {
					if ( !isset($value['enable']) || $value['enable'] !== true ) {
						// Hook removed or not enabled
						unset($extras[$type][$key]);
						unset($default[$type][$key]);

					} else {
 						if ( isset($default[$type][$key]['value']) ) {
							// Hook registred
							$extras[$type][$key]['value'] = $default[$type][$key]['value'];
						}
					}
				}
			}

			// Return filtered extras
			$hook->updatePluginOption(static::OPTION,$default,false);
			return $extras;
		}

		return false;
	}

	/**
	 * Register hooks inside group.
	 *
	 * @access public
	 * @param string $group
	 * @param PluginNameSpaceInterface $plugin
	 * @return void
	 */
	public static function register($group, PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$hook = parent::getStatic();
        $hook->initConfig($plugin);

		$hook->registerPluginOption($group,static::OPTION,false);
	}

	/**
	 * Add default hooks if not exists.
	 *
	 * @access public
	 * @param array $default
	 * @param PluginNameSpaceInterface $plugin
	 * @return bool
	 */
	public static function add($default, PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$hook = parent::getStatic();
        $hook->initConfig($plugin);

		return $hook->addPluginOption(static::OPTION,$default,false);
	}
}
