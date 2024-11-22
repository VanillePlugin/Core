<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

/**
 * Built-in crawler.
 */
final class Crawler extends Request
{
	/**
	 * @access private
	 * @var string PATTERN
	 */
	private const PATTERN = '*';

	/**
	 * @access private
	 * @var string $pattern
	 * @var array $args
	 * @var int $status
	 */
	private $pattern;
	private $args;
	private $status = 0;

	/**
	 * Init crawler.
	 *
	 * @param string $pattern
	 */
	public function __construct(string $pattern = self::PATTERN, array $args = [])
	{
		$this->pattern = $pattern;
		$this->args = Arrayify::merge([
			'method'      => self::GET,
			'timeout'     => 3,
			'redirection' => 2,
			'blocking'    => false,
			'headers'     => ['Cache-Control' => 'max-age=0']
		], $args);
	}

	/**
	 * Start crawler.
	 *
	 * @access public
	 * @param int $try
	 * @return bool
	 */
	public function start(int $try = 2) : bool
	{
		if ( self::canStart() ) {
			$try = ($try <= 5) ? $try : 2;
			foreach (Post::all() as $post) {
				if ( $this->pattern == '*' || $this->match($post['content']) ) {
					$this->ping($post['link'], $try);
				}
			}
		}
		return (bool)$this->status;
	}

	/**
	 * Ping url.
	 *
	 * @access private
	 * @param string $url
	 * @param int $try
	 * @return void
	 */
	private function ping(string $url, int $try = 2)
	{
		$i = 1;
		while ( $i <= $try ) {
			$response = self::do($url, $this->args);
			if ( self::getStatusCode($response) == 200 ) {
				$this->status += 1;
			}
			$i++;
			sleep(1);
		}
	}

	/**
	 * Check server capacity.
	 *
	 * @access private
	 * @return bool
	 */
	private function canStart() : bool
	{
		return (System::getCpuCores() >= 2) ?? false;
	}

	/**
	 * Match crawling post using content.
	 *
	 * @access private
	 * @param string $content
	 * @return bool
	 */
	private function match(string $content) : bool
	{
		return Stringify::match($this->pattern, $content);
	}
}
