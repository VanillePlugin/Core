<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.5.0
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

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
	 * Wrapp Wordpress database object
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
