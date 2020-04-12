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
 * Allowed to edit for plugin customization
 */

namespace winamaz\core\system\includes\thirdparty;

final class Wprocket
{
	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	static public function purge()
	{
		if (function_exists('rocket_clean_domain')) {
			rocket_clean_domain();
		}
	}
}
