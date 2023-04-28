<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

/**
 * Wrapper class for database Object.
 */
class Db extends PluginOptions
{
	/**
	 * @access public
	 * @var string $prefix, default prefix
	 * @var string $collate, collate
	 */
	public $prefix;
	public $collate;

	/**
	 * @access protected
	 * @var object $db | wpdb object
	 */
	protected $db;

	/**
	 * Wrap Wordpress database object.
	 *
	 * @access protected
	 * @param void
	 * @return void
	 */
	protected function init()
	{
		global $wpdb;
		$this->db = $wpdb;
		$this->prefix  = $this->db->prefix;
		$this->collate = $this->db->collate;
	}
}
