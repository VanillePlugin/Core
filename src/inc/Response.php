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

namespace VanillePlugin\inc;

final class Response extends Status
{
	/**
	 * @access public
	 * @param string TYPE default type
	 */
	public const TYPE = 'application/json';

	/**
	 * Set HTTP response.
	 *
	 * @param string $message
	 * @param mixed $content
	 * @param string $status
	 * @param int $code
	 * @return void
	 */
	public static function set(string $message, $content = [], string $status = 'success', int $code = 200)
	{
		self::setHttpHeaders($code);
		echo Json::encode([
			'status'  => $status,
			'code'    => $code,
			'message' => $message,
			'content' => $content
		]);
		die();
	}

	/**
	 * Set HTTP response header.
	 *
	 * @access public 
	 * @param int $code
	 * @param string $type
	 * @return void
	 */
	public static function setHttpHeaders(int $code, string $type = self::TYPE)
	{
		$status = self::getMessage($code);
		$protocol = Server::get('server-protocol');
		header("Content-Type: {$type}");
		header("{$protocol} {$code} {$status}");
	}
}
