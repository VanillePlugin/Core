<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

/**
 * Wrapper class for database object.
 */
class Db
{
	/**
	 * @access public
	 * @var string $prefix, Base prefix
	 * @var string $collate, Collate
	 */
	public $prefix;
	public $collate;

	/**
	 * @access protected
	 * @var object $db, wpdb object
	 */
	protected $db;

	/**
	 * Init db object.
	 */
	public function __construct()
	{
		global $wpdb;
		$this->db = $wpdb;
		$this->prefix = $this->db->prefix;
		$this->collate = $this->db->collate;
	}
}
