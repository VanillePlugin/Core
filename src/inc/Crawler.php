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
 * Built-in crawler.
 * Used in servers with enough capacity.
 */
final class Crawler
{
	/**
	 * @access private
	 * @var string METHOD
	 * @var string PATTERN
	 */
	private const METHOD  = 'GET';
	private const PATTERN = '*';

	/**
	 * @access private
	 * @var string $method
	 * @var string $pattern
	 */
	private $method;
	private $pattern;

	/**
	 * Init crawler.
	 *
	 * @param string $pattern
	 * @param string $method
	 */
	public function __construct(string $pattern = self::PATTERN, string $method = self::METHOD)
	{
		$this->pattern = $pattern;
		$this->method  = $method;
	}

	/**
	 * Start crawler.
	 *
	 * @access public
	 * @param int $depth
	 * @param array $args
	 * @return void
	 */
	public function start(int $depth = 2, array $args = [])
	{
		if ( self::canStart() ) {

			// Set request args
			$args = Arrayify::merge([
				'timeout'     => 10,
				'redirection' => 2,
				'user-agent'  => 'VanillePlugin/crawler',
				'headers'     => [
					'Cache-Control' => 'max-age=0'
				]
			], $args);

			// Init request client
			$api = new Request($this->method, $args);

			// Loop through posts
			foreach (Post::all() as $post) {
				$content = $post->post_content;
				if ( $this->pattern == '*' || $this->match($content) ) {
					$i = 1;
					$url = $post->guid;
					while ( $i <= $depth ) {
						$api->send($url);
					    $i++;
					    sleep(1);
					}
				}
			}
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
