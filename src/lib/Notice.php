<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\int\NoticeInterface;

/**
 * Plugin admin notice.
 */
class Notice extends View implements NoticeInterface
{
	/**
	 * @inheritdoc
	 */
    public function add($callable)
	{
		$this->addAction('admin_notices', $callable);
	}
}
