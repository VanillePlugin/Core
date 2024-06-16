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

use \WP_Query as Core;

class Query extends Core
{
	/**
	 * @inheritdoc
	 */
	public function __construct(array $args = [])
	{
		parent::__construct($args);
	}

	/**
	 * Check loop posts.
	 *
	 * @access public
	 * @return bool
	 */
	public function havePosts() : bool
	{
		return $this->have_posts();
	}

	/**
	 * Load post query.
	 *
	 * @access public
	 * @return mixed
	 */
	public function load()
	{
		return $this->the_post();
	}

	/**
	 * Reset query after loop.
	 *
	 * @access public
	 * @return mixed
	 */
	public function reset()
	{
		return wp_reset_postdata();
	}

	/**
	 * Get loop post Id.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getId()
	{
		return get_the_ID();
	}

	/**
	 * Get loop post title.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getTitle()
	{
		return get_the_title();
	}

	/**
     * Get query formatted args.
     *
	 * @access private
	 * @param mixed $args
	 * @return mixed
	 */
	private static function format($args)
	{
        if ( $args ) {
            return [];
        }
        return $args;
	}
}
