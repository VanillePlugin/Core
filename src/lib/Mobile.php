<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\{
    HttpRequest, Server, GlobalConst, Stringify
};

class Mobile extends View
{
	use \VanillePlugin\tr\TraitLoggable;

	/**
	 * @access public
	 * @var string ENDPOINT
	 * @var string USERAGENT
	 * @var array EXCEPTION
	 * @var array EXTERNAL
	 * @var array PROTOCOL
	 * @var array VIEWPORT
	 */
    public const ENDPOINT  = 'mobile';
    public const USERAGENT = 'mobile-app';
    public const EXCEPTION = [];
    public const EXTERNAL  = [];
    public const PROTOCOL  = [
		'mailto',
		'tel',
		'sms',
		'whatsapp'
	];
    public const VIEWPORT = [
		'width=device-width',
		'initial-scale=1',
		'minimum-scale=1',
		'maximum-scale=1',
		'viewport-fit=cover'
	];

	/**
	 * @access protected
	 * @var string $ua
	 */
	protected $ua;

	/**
	 * Init mobile.
	 */
	public function __construct()
	{
		$this->ua = $this->getNameSpace() . static::USERAGENT;
	}

	/**
	 * Display mobile interface.
	 *
	 * @access public
	 * @return void
	 */
	public function display()
	{
		$this->getHeader();
		echo static::ENDPOINT;
		die();
	}

	/**
	 * Check mobile app.
	 *
	 * @access public
	 * @return bool
	 */
	public function isApp() : bool
	{
		if ( !$this->isAjax() && !$this->isApi() ) {

			$ua = $this->getServer('http-user-agent');
			
			if ( $this->hasDebug() ) {
				$this->debug("User-Agent: {$ua}");
				return $this->isMobile();
			}
			
			return $this->hasString($ua, $this->ua);
		}

		return false;
	}

	/**
	 * Get mobile url.
	 *
	 * @access public
	 * @param string $url
	 * @return string
	 */
	public static function getUrl(?string $url = null) : string
	{
		$base = GlobalConst::url(static::ENDPOINT) . '/';
		if ( $url ) {
			$base .= $url;
		}
		return $base;
	}

	/**
	 * Get mobile viewport.
	 * 
	 * @access public
	 * @return string
	 */
	public static function getViewport() : string
	{
		return implode(', ', static::VIEWPORT);
	}

	/**
	 * Check mobile endpoint.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isEndpoint() : bool
	{
		$request  = Server::get('request-uri');
		$endpoint = '/'. static::ENDPOINT . '/';
		return Stringify::contains($request, $endpoint);
	}

	/**
	 * Check exception endpoint.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isException() : bool
	{
		$request = Server::get('request-uri');
		foreach (static::EXCEPTION as $exception) {
			if ( Stringify::contains($request, $exception) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check mobile page.
	 *
	 * @access public
	 * @param string $page
	 * @return bool
	 */
	public static function isPage(string $page) : bool
	{
		if ( static::isEndpoint() ) {
			return static::getPage() == $page;
		}
		return false;
	}

	/**
	 * Get mobile page.
	 *
	 * @access public
	 * @return string
	 */
	public static function getPage() : string
	{
		$page = HttpRequest::get('page');
		if ( !$page ) $page = 'index';
		return (string)$page;
	}

	/**
	 * Get mobile data.
	 *
	 * @access public
	 * @return array
	 */
	public static function getData() : array
	{
		$data = HttpRequest::get('data');
		if ( !$data ) $data = [];
		return (array)$data;
	}

	/**
	 * Get mobile HTTP header.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getHeader() : string
	{
		header('Content-Type: text/html');
		header('HTTP/1.1 200 OK');
	}
}
