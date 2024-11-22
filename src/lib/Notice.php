<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
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
 * Plugin notice manager.
 */
class Notice extends View implements NoticeInterface
{
	/**
	 * @inheritdoc
	 */
    public final function display(callable $callable)
	{
		$this->addAction('admin-notices', $callable);
	}

	/**
	 * @inheritdoc
	 */
    public function do(string $message, string $type = 'info', array $args = [])
	{
		$args = $this->mergeArray([
			'icon'   => 'info',
			'class'  => 'notice',
			'title'  => 'Notice',
			'detail' => false
		], $args);

		$this->render('admin/inc/notice', [
			'message' => $message,
			'type'    => $type,
			'args'    => $args
		]);
	}
}
