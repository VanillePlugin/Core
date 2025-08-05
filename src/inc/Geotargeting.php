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

/**
 * For high-performance applications use MaxMind GeoIP2 database.
 * @see https://dev.maxmind.com/geoip
 */
final class Geotargeting
{
	/**
	 * @access private
	 * @var string $visitorKey, Cache key
	 * @var array $exception, Exception IP addresses
	 * @var array $default, Default APIs
	 */
	private static $visitorKey = '--visitor-id';
	private static $exception = ['::1', '127.0.0.1', '0.0.0.0'];
	private static $default = [
		'ip'  => [
			'address' => 'https://api.ipify.org/?format=json',
			'param'   => 'ip'
		],
		'geo' => [
			'address' => 'http://ip-api.com/json/{ip}',
			'param'   => 'countryCode'
		]
	];

	/**
	 * @access private
	 * @var array $api, Geotargeting APIs
	 * @var string $target, Geotargeting target code
	 * @var string $code, External country code
	 * @var int $ttl, APIs response ttl
	 */
	private $api = [];
	private $target = false;
	private $code = false;
	private $ttl = false;

	/**
	 * Set redirection args.
	 *
	 * @param array $args
	 */
	public function __construct(array $args = [])
	{
		$this->api    = $args['api']    ?? [];
		$this->target = $args['target'] ?? false;
		$this->code   = $args['code']   ?? false;
		$this->ttl    = $args['ttl']    ?? false;

		if (!$this->ttl) {
			$this->ttl = time() + (86400 * 30);
		}

		$this->api = Arrayify::merge(self::$default, (array)$this->api);
	}

	/**
	 * Get status.
	 *
	 * @access public
	 * @return bool
	 */
	public function isDetected(): bool
	{
		return ($this->getCountryCode() === $this->target);
	}

	/**
	 * Set visitor key.
	 *
	 * @access public
	 * @param string $visitorKey
	 * @return void
	 */
	public static function setVisitorKey(string $visitorKey)
	{
		self::$visitorKey = $visitorKey;
	}

	/**
	 * Set exception IP addresses.
	 *
	 * @access public
	 * @param array $exception
	 * @return void
	 */
	public static function setException(array $exception)
	{
		self::$exception = $exception;
	}

	/**
	 * Format country code (Fix 3 DIGIT ISO),
	 * Lowercased ISO (3166‑1 alpha‑2).
	 *
	 * @access public
	 * @param string $code
	 * @return string
	 * @see https://countrycode.org/
	 */
	public static function formatCountryCode(string $code): string
	{
		return substr(strtolower($code), 0, 2);
	}

	/**
	 * Get current country code.
	 *
	 * @access public
	 * @return string
	 */
	public function getCountryCode(): string
	{
		if ($this->code) {
			return $this->code;
		}

		// Get visitor IP
		if (!($ip = Server::getIp())) {
			$ip = '0.0.0.0';
		}

		// Get IP from external API
		if (Arrayify::inArray($ip, self::$exception)) {
			$ip = $this->getApiAddressIp();
		}

		// Get code from external API
		return $this->getApiCountryCode($ip);
	}

	/**
	 * Get current country code using API.
	 *
	 * @access private
	 * @param string $ip
	 * @return string
	 */
	private function getApiCountryCode(string $ip = '0.0.0.0'): string
	{
		$code	 = '';
		$address = $this->api['geo']['address'] ?? '';
		$param   = $this->api['geo']['param']   ?? '';

		if (!$address || !$param) {
			return $code;
		}

		$key = "geo-{$this->getVisitorId()}";
		if (!($code = Cache::get($key))) {

			$address  = Stringify::replace('{ip}', $ip, $address);
			$response = Request::get($address, ['timeout' => 1]);

			if (Request::getStatusCode($response) == 200) {
				$response = Json::decode(Request::getBody($response), true);
				$code = $response[$param] ?? '';
			}

			Cache::set($key, $code, $this->ttl);
		}

		return self::formatCountryCode(
			(string)$code
		);
	}

	/**
	 * Get current IP address using API.
	 *
	 * @access private
	 * @return string
	 */
	private function getApiAddressIp(): string
	{
		$ip      = '0.0.0.0';
		$address = $this->api['ip']['address'] ?? '';
		$param   = $this->api['ip']['param'] ?? '';

		if (!$address || !$param) {
			return $ip;
		}

		$key = "ip-{$this->getVisitorId()}";

		if (!($ip = Cache::get($key))) {

			$response = Request::get($address, ['timeout' => 1]);
			if (Request::getStatusCode($response) == 200) {
				$response = Json::decode(Request::getBody($response), true);
				$ip = $response[$param] ?? '';
			}

			Cache::set($key, $ip, $this->ttl);
		}

		return (string)$ip;
	}

	/**
	 * Get visitor ID.
	 *
	 * @access private
	 * @return string
	 */
	private function getVisitorId(): string
	{
		if (Cookie::isSetted(self::$visitorKey)) {
			return Cookie::get(self::$visitorKey);
		}

		$visitorId = uniqid();
		Cookie::set(self::$visitorKey, $visitorId, [
			'expires' => $this->ttl,
			'path'    => '/'
		]);

		return $visitorId;
	}
}
