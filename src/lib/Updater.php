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

namespace VanillePlugin\lib;

use VanillePlugin\int\UpdaterInterface;
use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\inc\Arrayify;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\Server;
use VanillePlugin\inc\GlobalConst;
use \stdClass;

final class Updater extends PluginOptions implements UpdaterInterface
{
	/**
	 * @access private
	 * @var string $updateUrl
	 * @var string $infoUrl
	 * @var string $translationUrl
	 * @var string $version
	 * @var string $wpVersion
	 * @var array $license
	 * @var array $headers
	 * @var array $params
	 */
	private $updateUrl;
	private $infoUrl;
	private $translationUrl;
	private $version;
	private $wpVersion;
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

		// Parse plugin version
		$pluginHeader = $this->getPluginHeader($this->getMainFile());
		$version = !empty($pluginHeader['Version'])
		? $pluginHeader['Version'] : $this->getPluginVersion();

		// Init updater config
		$this->wpVersion = GlobalConst::version();
		$this->updateUrl = $host;
		$this->version = $version;
		
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
		$this->addFilter('pre_set_site_transient_update_plugins', [$this,'checkUpdate']);

		/**
		 * Check plugin translation update
		 * Filter : pre_set_site_transient_{$transient}
		 * Filter : site_transient_update_{$transient}
		 *
		 * @see checkTranslation@self
		 * @property priority 10
		 * @property count 1
		 */
		$this->addFilter('pre_set_site_transient_update_plugins', [$this,'checkTranslation']);

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
	 * @param object $args
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
				return $transient;
			}

			// Get response from cache
			$response = $this->getTransient('get-info');

			if ( !$response ) {

				// Set request params
				$params = [
					'slug'      => $this->getNameSpace(),
					'version'   => $this->version,
					'wpversion' => $this->wpVersion,
					'ua'        => $this->getUserAgent(),
					'action'    => "{$this->getNameSpace()}-get-info"
				];

				// Additional params
				$params = Arrayify::merge($params,$this->params);

				// Build request query
				$query = [
					'headers'    => $this->headers,
					'timeout'    => $this->getTimeout(),
					'sslverify'  => $this->isSSL(),
					'body'       => ['request' => Stringify::serialize($params)],
					'user-agent' => $this->getUserAgent()
				];

				// Set licence
				$query = $this->setLicense($query);

				// Get temp response
				$client = new Request();
				$temp = $client->get($this->infoUrl,$query);

				// Cache on success
				if ( $temp->getStatusCode() == 200 ) {
					if ( !empty($body = $temp->getBody()) ) {
						$response = unserialize($body);
						$ttl = $this->applyPluginFilter('updater-info-ttl',3600);
						$this->setTransient('get-info',$response,$ttl);
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
		// Get response from cache
		$response = $this->getTransient('check-update');
		
		if ( !$response ) {

			// Set request params
			$params = [
				'slug'      => $this->getNameSpace(),
				'version'   => $this->version,
				'wpversion' => $this->wpVersion,
				'ua'        => $this->getUserAgent(),
				'action'    => "{$this->getNameSpace()}-check-update"
			];

			// Additional params
			$params = Arrayify::merge($params,$this->params);
			
			// Build request query
			$query = [
				'headers'    => $this->headers,
				'timeout'    => $this->getTimeout(),
				'sslverify'  => $this->isSSL(),
				'body'       => ['request' => Stringify::serialize($params)],
				'user-agent' => $this->getUserAgent()
			];

			// Set licence
			$query = $this->setLicense($query);

			// Get temp response
			$client = new Request();
			$temp = $client->get($this->updateUrl,$query);

			// Cache on success
			if ( $temp->getStatusCode() == 200 ) {
				if ( !empty($body = $temp->getBody()) ) {
					$response = unserialize($body);
					$ttl = $this->applyPluginFilter('updater-update-ttl',3600);
					$this->setTransient('check-update',$response,$ttl);
				} else {
					$this->deleteTransient('check-update');
				}
			}
		}

		// Fix transient
		if ( !TypeCheck::isObject($transient) ) {
			$transient = new stdClass();
		}

		// Update transient
		if ( $this->isValidResponse($response) ) {
			$transient->response[$this->getMainFile()] = $response;

		} else {
	        $item = (object)[
	            'id'            => $this->getMainFile(),
	            'slug'          => $this->getNameSpace(),
	            'plugin'        => $this->getMainFile(),
	            'new_version'   => $this->version,
	            'url'           => '',
	            'package'       => '',
	            'icons'         => [],
	            'banners'       => [],
	            'banners_rtl'   => [],
	            'tested'        => '',
	            'requires_php'  => '',
	            'compatibility' => new stdClass()
	        ];
	        $transient->no_update[$this->getMainFile()] = $item;
		}

		$transient->last_checked = time();
		$transient->checked[$this->getMainFile()] = $this->version;

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

			// Set request params
			$params = [
				'slug'      => $this->getNameSpace(),
				'version'   => $this->version,
				'wpversion' => $this->wpVersion,
				'ua'        => $this->getUserAgent(),
				'action'    => "{$this->getNameSpace()}-check-translation"
			];

			// Additional params
			$params = Arrayify::merge($params,$this->params);

			// Build request query
			$query = [
				'headers'    => $this->headers,
				'timeout'    => $this->getTimeout(),
				'sslverify'  => $this->isSSL(),
				'body'       => ['request' => Stringify::serialize($params)],
				'user-agent' => $this->getUserAgent()
			];

			// Set licence
			$query = $this->setLicense($query);

			// Get temp response
			$client = new Request();
			$temp = $client->get($this->translationUrl,$query);

			// Cache on success
			if ( $temp->getStatusCode() == 200 ) {
				if ( !empty($body = $temp->getBody()) ) {
					$response = unserialize($body);
					$ttl = $this->applyPluginFilter('updater-translation-ttl',3600);
					$this->setTransient('check-translation',$response,3600);
				} else {
					$this->deleteTransient('check-translation');
				}
			}
		}

		// Update transient
		if ( $this->isValidTranslateResponse($response) ) {
			if ( isset($response->translations) ) {
				$installed = $this->getInstalledTranslations();
				foreach ($response->translations as $key => $translation) {
					$language = $translation['language'];
					if ( isset($installed[$this->getNameSpace()][$language]) ) {
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
	 * @param void
	 * @return string
	 */
	private function getUserAgent()
	{
		return "{$this->getNameSpace()}-wordpress/{$this->version};";
	}

	/**
	 * @access private
	 * @param void
	 * @return string
	 */
	private function getTimeout()
	{
		return $this->applyPluginFilter('updater-timeout',10);
	}

	/**
	 * @access private
	 * @param void
	 * @return bool
	 */
	private function isSSL()
	{
		return $this->applyPluginFilter('updater-ssl',Server::isSSL());
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

	/**
	 * @access private
	 * @param object $response
	 * @return bool
	 */
	private function isValidTranslateResponse($response)
	{
		if ( TypeCheck::isObject($response) && isset($response->translations) ) {
			return true;
		}
		return false;
	}
}
