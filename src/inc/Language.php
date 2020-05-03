<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use VanillePlugin\lib\PluginOptions;

final class Language extends PluginOptions
{
	/**
	 * @param boolean $display
	 * @return string
	 */
	public static function get($display = false)
	{
		$plugin = parent::getStatic();
		$plugin->initConfig();
		return wp_dropdown_languages([
			'id' 		=> "{$plugin->getNameSpace()}-lang",
			'name' 		=> "{$plugin->getNameSpace()}-lang",
			'languages' => get_available_languages(),
			'echo'      => false
		]);
	}

	/**
	 * @param bool $lang
	 * @return json
	 */
	public static function format($lang)
	{
		return substr($lang, 0, strpos($lang, '_'));
	}
}
