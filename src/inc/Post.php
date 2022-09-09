<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.8
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
	 * @param int $id
	 * @return object
	 */
	public static function get($id = null)
	{
		if ( $id ) {
			$post = get_post($id);
		} else {
			global $post;
		}
		return (object)$post;
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
	 * @param void
	 * @return string
	 */
	public static function getTitle($id = null)
	{
		$id = ($id) ? $id : self::getId();
		$title = get_the_title($id);
		return !empty($title) ? $title : 'Archive page';
	}

	/**
	 * @access public
	 * @param array $args
	 * @param string $output
	 * @param string $operator
	 * @return array
	 */
	public static function getTypes($args = [], $output = 'names', $operator = 'and')
	{
		return get_post_types($args,$output,$operator);
	}

	/**
	 * @access public
	 * @param array $data
	 * @param bool $error
	 * @param bool $after
	 * @return mixed
	 */
	public static function add($data = [], $error = false, $after = true)
	{
		return wp_insert_post($data,$error,$after);
	}

	/**
	 * @access public
	 * @param array $data
	 * @param bool $error
	 * @param bool $after
	 * @return mixed
	 */
	public static function update($data = [], $error = false, $after = true)
	{
		return wp_update_post($data,$error,$after);
	}

	/**
	 * @access public
	 * @param array $id
	 * @param bool $force
	 * @return mixed
	 */
	public static function delete($id = false, $force = false)
	{
		$id = ($id) ? $id : self::getId();
		return wp_delete_post($id,$force);
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
