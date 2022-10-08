<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.8.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

namespace VanillePlugin\inc;

final class Attachment
{
	/**
	 * Insert attachment,
	 * Returns attachment data if success.
	 *
	 * @access public
	 * @param string $path
	 * @param array $data
	 * @return mixed
	 */
	public static function insert($path, $data)
	{
		$data = self::defaultArgs($data);
		if ( ($id = self::preInsert($path,$data)) ) {
			self::update($id);
		    return [
		    	'id'  => $id,
		    	'url' => $data['url']
		    ];
		}
		return false;
	}

	/**
	 * Pre-insert attachment,
	 * Returns attachment Id if success.
	 *
	 * @access public
	 * @param string $path
	 * @param array $data
	 * @return int
	 */
	public static function preInsert($path, $data)
	{
		$data = self::defaultArgs($data);
		if ( empty($data['title']) ) {
			$data['title'] = File::getFileName($path);
		}
		$attachment = [
	        'guid'           => $data['url'],
	        'post_title'     => Stringify::sanitizeText($data['title']),
	        'post_content'   => Stringify::sanitizeText($data['content']),
	        'post_excerpt'   => Stringify::sanitizeText($data['excerpt']),
	        'post_mime_type' => $data['type']
		];
		return (int)wp_insert_attachment($attachment,$path,$data['parent']);
	}

	/**
	 * Update attachment by Id.
	 *
	 * @access public
	 * @param int $id
	 * @return bool
	 */
	public static function update($id)
	{
		$post = Post::get($id);
		$path = self::getAttachedFile($post->ID);
		$meta = self::generateMeta($id,$path);
		return (bool)self::updateMeta($id,$meta);
	}

	/**
	 * Get attached file path by Id.
	 *
	 * @access public
	 * @param int $id
	 * @return string
	 */
	public static function getAttachedFile($id)
	{
		return (string)get_attached_file($id);
	}

	/**
	 * Generate attachment meta data by Id.
	 *
	 * @access public
	 * @param int $id
	 * @param string $path
	 * @return array
	 */
	public static function generateMeta($id,$path)
	{
		if ( !TypeCheck::isFunction('wp_generate_attachment_metadata') ) {
		    require_once(GlobalConst::rootDir('wp-admin/includes/image.php'));
		}
		return (array)wp_generate_attachment_metadata($id,$path);
	}

	/**
	 * Update attachment meta data by Id.
	 *
	 * @access public
	 * @param int $id
	 * @param array $data
	 * @return bool
	 */
	public static function updateMeta($id,$data)
	{
		return (bool)wp_update_attachment_metadata($id,$data);
	}

	/**
	 * Get attachment Id by title.
	 *
	 * @access public
	 * @param string $title
	 * @return int
	 */
	public static function getIdByTitle($title)
	{
		return self::getIdByUrl(
			self::getUrlByTitle($title)
		);
	}

	/**
	 * Get attachment url by Id.
	 *
	 * @access public
	 * @param int $id
	 * @return string
	 */
	public static function getUrlById($id)
	{
		return wp_get_attachment_url($id);
	}

	/**
	 * Get attachment Id by url.
	 *
	 * @access public
	 * @param string $url
	 * @return int
	 */
	public static function getIdByUrl($url)
	{
		return attachment_url_to_postid($url);
	}

	/**
	 * Get attachment url by title.
	 *
	 * @access public
	 * @param string $title
	 * @return mixed
	 */
	public static function getUrlByTitle($title)
	{
		if ( ($attachment = get_page_by_title($title,OBJECT,'attachment')) ) {
			return $attachment->guid;
		}
		return false;
	}

	/**
	 * Get attachment image source by id.
	 *
	 * @access public
	 * @param int $id
	 * @param string $size
	 * @return string
	 */
	public static function getImageById($id, $size = 'thumbnail')
	{
		$src = wp_get_attachment_image_src($id,$size);
		return isset($src[0]) ? $src[0] : '';
	}

	/**
	 * Insert attachment.
	 *
	 * @access public
	 * @param array $args
	 * @return array
	 */
	public static function defaultArgs($args)
	{
		$default = [
			'url'     => '',
			'title'   => '',
			'content' => '',
			'excerpt' => '',
			'type'    => '',
			'parent'  => 0
		];
		return Arrayify::merge($default,$args);
	}
}
