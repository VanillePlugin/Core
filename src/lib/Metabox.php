<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

/**
 * Plugin metabox.
 */
class Metabox extends View
{
	/**
	 * @access protected
	 * @var array EXCLUDE, Excluded post types
	 */
	protected const EXCLUDE = [
		'attachment',
		'revision',
		'nav_menu_item',
		'custom_css',
		'customize_changeset',
		'user_request',
	];

	/**
	 * @inheritdoc
	 */
	public function init($callable)
	{
		$this->addAction('admin-notices', $callable);
	}

	/**
	 * @inheritdoc
	 */
	public function add($callable)
	{
		$this->addAction('add-meta-boxes-', $callable);
	}

	/**
	 * @inheritdoc
	 */
	public function create($callable)
	{
		$this->addAction('add-meta-boxes-', $callable);
	}

	/**
	 * Init metabox.
	 */
	public function __construct() {}
}
