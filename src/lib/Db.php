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

namespace VanillePlugin\lib;

use VanillePlugin\int\ConfigInterface;

class Db
{
	/**
	 * @access public
	 * @var string $prefix | db prefix
	 * @var string $collate | collate
	 * @var string $basePrefix | table prefix
	 */
	public $prefix;
	public $collate;
	public $basePrefix;

	/**
	 * @access protected
	 * @var object $db | wpdb object
	 */
	protected $db;

	/**
	 * Wrapp Wordpress database object
	 *
	 * @access protected
	 * @param ConfigInterface $config
	 * @return void
	 */
	protected function init(ConfigInterface $config = null)
	{
		global $wpdb;
		$this->db = $wpdb;
		$this->prefix  = $this->db->prefix;
		$this->collate = $this->db->collate;
		$this->basePrefix = $config->prefix;
	}
}
