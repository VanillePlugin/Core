<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.4.1
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\UpdaterInterface;
use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\TypeCheck;

final class Updater extends PluginOptions implements UpdaterInterface
{
	/**
	 * @access private
	 * @var string $updateUrl
	 * @var string $infoUrl
	 * @var string $translationUrl
	 * @var string $wpVerion
	 * @var array $license
	 * @var array $headers
	 * @var array $params
	 */
	private $updateUrl;
	private $infoUrl;
	private $translationUrl;
	private $wpVerion;
	private $license = [];
	private $headers = [];
	private $params = [];

	/**
	 * @param PluginNameSpaceInterface $plugin
	 * @param string $host
	 * @param array $params
	 *
	 * action : admin_init
	 */
	public function __construct(PluginNameSpaceInterface $plugin, $host, $params = [])
	{
		// Init plugin config
		$this->initConfig($plugin);

		// Check update capability
		if ( !$this->hadCapability('update_plugins') ) {
		    return;
		}

		// Check WordPress update
		if ( wp_installing() ) {
			return $transient;
		}

		// Init updater config
		global $wp_version;
		$this->wpVerion = $wp_version;
		$this->updateUrl = $host;
		
		// Define request
		$this->headers = isset($params['headers']) ? $params['headers'] : [];
		$this->license = isset($params['license']) ? $params['license'] : false;
		$this->infoUrl = isset($params['infoUrl']) ? $params['infoUrl'] : false;
		$this->translationUrl = isset($params['translationUrl']) ? $params['translationUrl'] : false;
		$this->params = $params;

		// Clean request params
		unset($this->params['headers']);
		unset($this->params['license']);
		unset($this->params['infoUrl']);
		unset($this->params['translationUrl']);

		/**
		 * Get plugin info
		 * Filter : plugins_api
		 *
		 * @see getInfo@self
		 * @property priority 10
		 * @property count 3
		 */
		$this->addFilter('plugins_api', [$this,'getInfo'], 10, 3);

		/**
		 * Check plugin update
		 * Filter : pre_set_site_transient_{$transient}
		 * Filter : site_transient_update_{$transient}
		 *
		 * @see checkUpdate@self
		 * @property priority 10
		 * @property count 1
		 */
		$this->addFilter('pre_set_site_transient_update_plugins', [$this,'checkUpdate'], 10);

		/**
		 * Check plugin translation update
		 * Filter : pre_set_site_transient_{$transient}
		 * Filter : site_transient_update_{$transient}
		 *
		 * @see checkTranslation@self
		 * @property priority 20
		 * @property count 1
		 */
		$this->addFilter('pre_set_site_transient_update_plugins', [$this,'checkTranslation'], 20);

		/**
		 * Clear plugin updates cache
		 * Filter : upgrader_process_complete
		 *
		 * @see clearUpdateCache@self
		 * @property priority 10
		 * @property count 2
		 */
		$this->addFilter('upgrader_process_complete', [$this,'clearUpdateCache'], 10, 2);
	}

	/**
	 * @access public
	 * @param object $transient
	 * @param string $action
	 * @param array $args
	 * @return mixed
	 */
	public function getInfo($transient, $action, $args)
	{
		// Check action
		if ( $action !== 'plugin_information' ) {
			return false;
		}

		if ( $args->slug === $this->getNameSpace() ) {

			// Check info option
			if ( !$this->infoUrl ) {
				return false;
			}

			// Get response from cache
			$response = $this->getTransient('get-info');
			if ( !$response ) {

				// Prepare response
				$info = $this->getPluginInfo($this->getMainFilePath());

				// Build query
				$params = [
					'slug'      => $this->getNameSpace(),
					'version'   => $info['Version'],
					'wpversion' => $this->wpVerion
				];
				$query = [
					'headers' => $this->headers,
					'body' => [
						'action'  => "{$this->getNameSpace()}-get-info",
						'request' => serialize($params),
						'params'  => $this->params
					],
					'user-agent' => "{$this->getNameSpace()}-wordpress/{$info['Version']};"
				];

				// Add licence
				$query = $this->setLicense($query);

				// Get temp response
				$client = new Request();
				$temp = $client->post($this->infoUrl,$query);

				// Cache on success
				if ( $temp->getStatusCode() == 200 ) {
					if ( !empty($body = $temp->getBody()) ) {
						$response = unserialize($body);
						$this->setTransient('get-info',$response,1800);
					} else {
						$this->deleteTransient('get-info');
					}
				}
			}

			// Update transient
			if ( $this->isValidResponse($response) ) {
				$transient = $response;
			}
		}

		return $transient;
	}

	/**
	 * @access public
	 * @param object $transient
	 * @return mixed
	 */
	public function checkUpdate($transient)
	{
		// Check transient
		if ( empty($transient->checked) ) {
			return $transient;
		}

		// Get response from cache
		$response = $this->getTransient('check-update');
		
		if ( !$response ) {

			// Get plugin version
			$version = isset($transient->checked[$this->getMainFile()])
			? $transient->checked[$this->getMainFile()] : '0.0.0';

			// Build query
			$params = [
				'slug'      => $this->getNameSpace(),
				'version'   => $version,
				'wpversion' => $this->wpVerion
			];
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

			// Get temp response & Update transient
			$client = new Request();
			$temp = $client->post($this->updateUrl,$query);

			// Cache on success
			if ( $temp->getStatusCode() == 200 ) {
				if ( !empty($body = $temp->getBody()) ) {
					$response = unserialize($body);
					$this->setTransient('check-update',$response,1800);
				} else {
					$this->deleteTransient('check-update');
				}
			}
		}

		// Update transient
		if ( $this->isValidResponse($response) ) {
			$transient->response[$this->getMainFile()] = $response;
		}

		return $transient;
	}

	/**
	 * @access public
	 * @param object $transient
	 * @return mixed
	 */
	public function checkTranslation($transient)
	{
		// Check translation option
		if ( !$this->translationUrl ) {
			return $transient;
		}

		// Get response from cache
		$response = $this->getTransient('check-translation');
		if ( !$response ) {

			// Get plugin version
			$version = isset($transient->checked[$this->getMainFile()])
			? $transient->checked[$this->getMainFile()] : '0.0.0';

			// Build query
			$params = [
				'slug'      => $this->getNameSpace(),
				'version'   => $version,
				'wpversion' => $this->wpVerion
			];
			$query = [
				'headers' => $this->headers,
				'body' => [
					'action'  => "{$this->getNameSpace()}-check-translation", 
					'request' => serialize($params),
					'params'  => $this->params
				],
				'user-agent' => "{$this->getNameSpace()}-wordpress/{$version};"
			];

			// Add licence
			$query = $this->setLicense($query);

			// Get temp response
			$client = new Request();
			$temp = $client->post($this->translationUrl,$query);

			// Cache on success
			if ( $temp->getStatusCode() == 200 ) {
				if ( !empty($body = $temp->getBody()) ) {
					$response = unserialize($body);
					$this->setTransient('check-translation',$response,1800);
				} else {
					$this->deleteTransient('check-translation');
				}
			}
		}

		// Update transient
		if ( $this->isValidResponse($response) ) {
			if ( isset($response->translations) ) {
				$installed = $this->getInstalledTranslations();
				foreach ($response->translations as $key => $translation) {
					$language = $translation['language'];
					if ( isset($installed[$this->getNameSpace()][$language]) ) {
						unset($response->translations[$key]);
					} elseif ( $language !== $this->getLanguage(true) ) {
						unset($response->translations[$key]);
					} else {
						$transient->translations[] = $translation;
					}
				}
			}
		}

		return $transient;
	}

	/**
	 * @access public
	 * @param object $upgrader
	 * @param array $options
	 * @return mixed
	 */
	public function clearUpdateCache($upgrader, $options)
	{
		if ( $options['action'] == 'update' && $options['type'] === 'plugin' )  {
			$this->deleteTransient('get-info');
			$this->deleteTransient('check-update');
			$this->deleteTransient('check-translation');
		}
	}

	/**
	 * @access public
	 * @param void
	 * @return array
	 */
	public function getInstalledTranslations()
	{
		return wp_get_installed_translations('plugins');
	}

	/**
	 * @access private
	 * @param array $query
	 * @return array
	 */
	private function setLicense($query)
	{
		if ( TypeCheck::isArray($this->license) ) {
			foreach ($this->license as $param => $value) {
				$query['body'][$param] = $value;
			}
		}
		return $query;
	}

	/**
	 * @access private
	 * @param object $response
	 * @return bool
	 */
	private function isValidResponse($response)
	{
		if ( TypeCheck::isObject($response) && isset($response->plugin) ) {
			if ( $response->plugin == $this->getMainFile() ) {
				return true;
			}
		}
		return false;
	}
}
