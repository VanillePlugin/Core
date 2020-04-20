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
 */

namespace VanillePlugin\inc;

final class Response
{
	/**
	 * @param string $message
	 * @param array $content
	 * @param string $status
	 * @return json
	 */
	public static function set($message = '', $content = [], $status = 'success')
	{
		echo Json::encode([
			'status'  => $status,
			'message' => $message,
			'content' => $content
		]);
		die();
	}

	/**
	 * @param string $reponse
	 * @param boolean $isArray
	 * @return mixed
	 */
	public static function get($reponse, $isArray = false)
	{
		return Json::decode($reponse,$isArray);
	}
}
