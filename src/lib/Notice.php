<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.2
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\NoticeInterface;

class Notice extends View implements NoticeInterface
{
	/**
	 * @access public
	 * @param array $callable
	 * @return void
	 */
	public function init($callable = [])
	{
		$this->addAction('admin_notices', $callable);
	}
}
