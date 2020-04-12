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
 * Allowed to edit for plugin customization
 */

namespace VanillePlugin\lib;

class Updater extends Settings
{
	/**
	 * @access private
	 * @var array $content
	 */
	private $hostUrl;
	private $siteUrl;
	private $wpVerion;
	private $pluginSlug;
	private $plugin;
	private $infoUrl;
	private $license = [];
	private $headers = [];
	private $params = [];
	private $unsafe = false;

	/**
	 * @param void
	 */
	function __construct($hostUrl, $file, $params = [], $forceUnsafe = false)
	{
		global $wp_version;
		$this->wpVerion = $wp_version;
		$this->hostUrl  = $hostUrl;

		if ($forceUnsafe) {
			$this->unsafe = parent::isHttps() ? false : true;
		} else {
			$this->unsafe = true;
		}
		
		// Define request
		$this->siteUrl    = get_bloginfo('url');
		$this->pluginSlug = basename(dirname($file));
		$this->plugin     = "{$this->pluginSlug}/{$this->pluginSlug}.php";
		$this->license    = isset($params['license']) ? $params['license'] : false;
		$this->headers    = isset($params['headers']) ? $params['headers'] : [];
		$this->infoUrl    = isset($params['infoUrl']) ? $params['infoUrl'] : false;
		$this->params     = $params;

		// Split params
		unset($this->params['headers']);
		unset($this->params['license']);
		unset($this->params['infoUrl']);

		// Hooks init
		$this->init();
	}

	/**
	 * @param void
	 * @return void
	 */
	public function init()
	{
		$this->addFilter('plugins_api', [$this,'infos'], 20, 3);
		$this->addFilter('pre_set_site_transient_update_plugins', [$this,'check'], 20);
		$this->addFilter('http_request_args', [$this,'setRequest'], 20);
	}

	/**
	 * Todo
	 *
	 * @param void
	 * @return void
	 */
	public function check($transient)
	{
		if ( empty($transient->checked) ) return $transient;

		$response = false;
		$version  = isset($transient->checked[$this->plugin])
		? $transient->checked[$this->plugin] : null;
		
		$params = [
			'slug'      => $this->pluginSlug,
			'version'   => $version,
			'wpversion' => $this->wpVerion
		];

		$query = [
			'headers' => $this->headers,
			'body' => [
				'action'  => "{$this->pluginSlug}-check-update", 
				'request' => serialize($params),
				'params'  => $this->params
			],
			'user-agent' => "{$this->pluginSlug}-wordpress/{$version};"
		];

		$query = $this->setLicense($query);
		$raw = wp_remote_post($this->hostUrl, $query);

		if ( !is_wp_error($raw) && ($raw['response']['code'] == 200) ) {
			$response = unserialize($raw['body']);
		}

		if ( is_object($response) && !empty($response) ) {
			$transient->response[$this->plugin] = $response;
		}
		
		return $transient;
	}

	/**
	 * @param object $transient
	 * @param string $action
	 * @param array $args
	 * @return mixed
	 */
	function infos($transient, $action, $args)
	{
		// // Check activated option
		if ( !$this->infoUrl ) return false;

		// // Check action
		if ( $action !== 'plugin_information' ) return false;

		if ( $args->slug === $this->pluginSlug ) {
			$pluginInfos = $this->pluginInfo($this->plugin);
			$params = [
				'slug'      => $this->pluginSlug,
				'version'   => $pluginInfos['Version'],
				'wpversion' => $this->wpVerion
			];
			$query = [
				'headers' => $this->headers,
				'body' => [
					'action'  => "{$this->pluginSlug}-get-info", 
					'request' => serialize($params),
					'params'  => $this->params
				],
				'user-agent' => "{$this->pluginSlug}-wordpress/{$pluginInfos['Version']};"
			];

			$query = $this->setLicense($query);
			$raw = wp_remote_post($this->infoUrl, $query);

			if ( !is_wp_error($raw) && ($raw['response']['code'] == 200) ) {
				$transient = unserialize($raw['body']);
			}
		}
		return $transient;
	}

	/**
	 * Todo
	 *
	 * @param void
	 * @return void
	 */
	private function setLicense($query)
	{
		if (is_array($this->license)) {
			foreach ($this->license as $param => $value) {
				$query['body'][$param] = $value;
			}
		}
		return $query;
	}

	/**
	 * Todo
	 *
	 * @param void
	 * @return void
	 */
	public function setRequest($args)
	{
		if ($this->unsafe) {
			$args['reject_unsafe_urls'] = false;
			return $args;
		}
	}
}
