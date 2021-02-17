<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.8
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\UpdaterInterface;
use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\Server;

class Updater extends PluginOptions implements UpdaterInterface
{
	/**
	 * @access private
	 * @var array $content
	 */
	private $host;
	private $siteUrl;
	private $wpVerion;
	private $infoUrl;
	private $license = [];
	private $headers = [];
	private $params = [];

	/**
	 * @param PluginNameSpaceInterface $plugin
	 * @param string $host
	 * @param array $params
	 * @return void
	 *
	 * action : admin_init
	 */
	public function __construct(PluginNameSpaceInterface $plugin, $host, $params = [])
	{
		// Init plugin config
		$this->initConfig($plugin);

		global $wp_version;
		$this->wpVerion = $wp_version;
		$this->host = $host;
		
		// Define request
		$this->siteUrl = get_bloginfo('url');
		$this->license = isset($params['license']) ? $params['license'] : false;
		$this->headers = isset($params['headers']) ? $params['headers'] : [];
		$this->infoUrl = isset($params['infoUrl']) ? $params['infoUrl'] : false;
		$this->params  = $params;

		// Split params
		unset($this->params['headers']);
		unset($this->params['license']);
		unset($this->params['infoUrl']);

		/**
		 * Get plugin info
		 * Filter : plugins_api
		 *
		 * @see infos@self
		 * @property priority 20
		 * @property count 3
		 */
		$this->addFilter('plugins_api', [$this,'infos'], 20, 3);

		/**
		 * Get plugin update
		 * Filter : pre_set_site_transient_update_plugins
		 *
		 * @see check@self
		 * @property priority 20
		 * @property count 1
		 */
		$this->addFilter('pre_set_site_transient_update_plugins', [$this,'check'], 20);
	}

	/**
	 * @access public
	 * @param object $transient
	 * @return mixed
	 */
	public function check($transient)
	{
		// Check transient
		if ( empty($transient->checked) ) {
			return $transient;
		}

		// Prepare response
		$response = false;
		$version  = isset($transient->checked[$this->getMainFile()])
		? $transient->checked[$this->getMainFile()] : null;
		
		$params = [
			'slug'      => $this->getNameSpace(),
			'version'   => $version,
			'wpversion' => $this->wpVerion
		];

		// Build query
		$query = [
			'headers' => $this->headers,
			'body' => [
				'action'  => "{$this->getNameSpace()}-check-update", 
				'request' => serialize($params),
				'params'  => $this->params
			],
			'user-agent' => "{$this->getNameSpace()}-wordpress/{$version};"
		];

		// Add licence
		$query = $this->setLicense($query);

		// Get response & Update transient
		$client = new Request();
		$response = $client->post($this->host, $query);
		if ( $response->getStatusCode() == 200 ) {
			$body = $response->getBody();
			if ( !empty($body) ) {
				$response = unserialize($body);
				if ( $this->isValid($response) ) {
					$transient->response[$this->getMainFile()] = $response;
				}
			}
		}

		return $transient;
	}

	/**
	 * @access public
	 * @param object $transient
	 * @param string $action
	 * @param array $args
	 * @return mixed
	 */
	public function infos($transient, $action, $args)
	{
		// Check activated option
		if ( !$this->infoUrl ) return false;

		// Check action
		if ( $action !== 'plugin_information' ) return false;

		if ( $args->slug === $this->getNameSpace() ) {

			// Prepare response
			$pluginInfos = $this->getPluginInfo($this->getMainFilePath());

			// Build query
			$params = [
				'slug'      => $this->getNameSpace(),
				'version'   => $pluginInfos['Version'],
				'wpversion' => $this->wpVerion
			];
			$query = [
				'headers' => $this->headers,
				'body' => [
					'action'  => "{$this->getNameSpace()}-get-info", 
					'request' => serialize($params),
					'params'  => $this->params
				],
				'user-agent' => "{$this->getNameSpace()}-wordpress/{$pluginInfos['Version']};"
			];

			// Add licence
			$query = $this->setLicense($query);

			// Get response & Update transient
			$client = new Request();
			$response = $client->post($this->infoUrl, $query);
			if ( $response->getStatusCode() == 200 ) {
				$body = $response->getBody();
				if ( !empty($body) ) {
					$response = unserialize($body);
					if ( $this->isValid($response) ) {
						$transient = $response;
					}
				}
			}
		}

		return $transient;
	}

	/**
	 * @access private
	 * @param array $query
	 * @return array
	 */
	private function setLicense($query)
	{
		if ( is_array($this->license) ) {
			foreach ($this->license as $param => $value) {
				$query['body'][$param] = $value;
			}
		}
		return $query;
	}

	/**
	 * @access private
	 * @param object $response
	 * @return boolean
	 */
	private function isValid($response)
	{
		if ( is_object($response) && !empty($response) ) {
			return true;
		}
		return false;
	}
}
