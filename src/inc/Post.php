<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.1
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Post
{
	/**
	 * @access public
	 * @param void
	 * @return object
	 */
	public static function get()
	{
		global $post;
		return $post;
	}

	/**
	 * @access public
	 * @param void
	 * @return int
	 */
	public static function getId()
	{
		global $post;
		return isset($post->ID) ? $post->ID : false;
	}

	/**
	 * @access public
	 * @param string $key
	 * @param int $id
	 * @param bool $single
	 * @return mixed
	 */
	public static function getMeta($key = '', $id = false, $single = true)
	{
		$id = ($id) ? $id : self::getId();
		return get_post_meta($id,$key,$single);
	}

	/**
	 * @access public
	 * @param string $key
	 * @param string $value
	 * @param int $id
	 * @return bool
	 */
	public static function updateMeta($key, $value, $id = false)
	{
		$id = ($id) ? $id : self::getId();
		return update_post_meta($id,$key,$value);
	}

	/**
	 * @access public
	 * @param string $key
	 * @param string $value
	 * @param int $id
	 * @return bool
	 */
	public static function deleteMeta($key, $value = '', $id = false)
	{
		$id = ($id) ? $id : self::getId();
		return delete_post_meta($id,$key,$value);
	}
}
