<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\int\UpdaterInterface;
use \stdClass;

/**
 * Wrapper class for self-hosted plugin.
 */
class Updater implements UpdaterInterface
{
    use \VanillePlugin\VanillePluginOption;

	/**
	 * @access protected
	 * @var string $updateUrl, Updater API update URL
	 * @var string $infoUrl, Updater API info URL
	 * @var string $translationUrl, Updater API translation URL
	 * @var string $assetUrl, Updater API asset URL
	 * @var string $version, Updater plugin version
	 * @var string $wpVersion, Updater WP version
	 * @var array $license, Updater API license
	 * @var array $headers, Updater API headers
	 * @var mixed $auth, Updater API authentication
	 * @var array $args, Updater API additional args
	 * @var array $pluginHeader, Plugin header
	 */
	protected $updateUrl;
	protected $infoUrl;
	protected $translationUrl;
	protected $assetUrl;
	protected $version;
	protected $wpVersion;
	protected $license = [];
	protected $headers = [];
	protected $auth;
	protected $args = [];
	protected $pluginHeader = [];

	/**
	 * @inheritdoc
	 */
	public function __construct(?string $host = null, array $args = [])
	{
		if ( !$host ) return;

		// Parse plugin version
		$this->pluginHeader = $this->getPluginHeader($this->getMainFile());
		$this->version = !empty($this->pluginHeader['Version'])
		? $this->pluginHeader['Version'] : $this->getPluginVersion();

		// Init updater config
		$this->wpVersion = $this->getVersion();
		$this->updateUrl = $host;
		
		// Set updater request data
		$this->headers        = $args['headers']        ?? [];
		$this->auth           = $args['auth']           ?? false;
		$this->license        = $args['license']        ?? false;
		$this->infoUrl        = $args['infoUrl']        ?? false;
		$this->translationUrl = $args['translationUrl'] ?? false;
		$this->assetUrl       = $args['assetUrl']       ?? false;
		$this->args           = $args;

		// Clean updater request args
		unset($this->args['headers']);
		unset($this->args['auth']);
		unset($this->args['license']);
		unset($this->args['infoUrl']);
		unset($this->args['translationUrl']);
		unset($this->args['assetUrl']);

		/**
		 * Get plugin info,
		 * [Filter: plugins_api].
		 *
		 * @see getInfo@self
		 * @property priority 10
		 * @property count 3
		 */
		$this->addFilter('plugins_api', [$this, 'getInfo'], 10, 3);

		/**
		 * Check plugin update,
		 * [Filter: pre_set_site_transient_{$transient}],
		 * [Filter: site_transient_update_{$transient}].
		 *
		 * @see checkUpdate@self
		 * @property priority 10
		 * @property count 1
		 */
		$this->addFilter('pre_set_site_transient_update_plugins', [$this, 'checkUpdate']);

		/**
		 * Check plugin translation update,
		 * [Filter: pre_set_site_transient_{$transient}],
		 * [Filter: site_transient_update_{$transient}].
		 *
		 * @see checkTranslation@self
		 * @property priority 10
		 * @property count 1
		 */
		$this->addFilter('pre_set_site_transient_update_plugins', [$this, 'checkTranslation']);

		/**
		 * Clear plugin updates cache,
		 * [Filter: upgrader_process_complete].
		 *
		 * @see clearCache@self
		 * @property priority 10
		 * @property count 2
		 */
		$this->addFilter('upgrader_process_complete', [$this, 'clearCache'], 10, 2);

		/**
		 * Filter updater args,
		 * [Filter: http_request_args].
		 *
		 * @see filterArgs@self
		 * @property priority 20
		 * @property count 1
		 */
		$this->addFilter('http_request_args', [$this, 'filterArgs'], 20);
	}

	/**
	 * @inheritdoc
	 */
	public function getInfo($transient, string $action, object $args)
	{
		// Check action
		if ( $action !== 'plugin_information' ) {
			return false;
		}

		// Check plugin
		if ( $args->slug === $this->getNameSpace() ) {

			// Check info API URL
			if ( !$this->infoUrl ) {
				return $transient;
			}

			// Fetch info
			$info = $this->fetch('get-info', $this->infoUrl);

			// Update transient
			if ( $this->isValid('info', $info) ) {
				$transient = $info;

			} else {
				$transient = $this->getDefaultTransient('info');
			}
		}

		return $transient;
	}

	/**
	 * @inheritdoc
	 */
	public function checkUpdate($transient) : object
	{
		// Fix transient
		if ( !$this->isType('object', $transient) ) {
			$transient = new stdClass();
		}

		// Fetch update
		$update = $this->fetch('check-update', $this->updateUrl);

		// Update transient
		if ( $this->isValid('update',$update) ) {
			$transient->response[$this->getMainFile()] = $update;

		} else {
	        $transient->no_update[$this->getMainFile()] = $this->getDefaultTransient('update');
		}

		// Check transient
		$transient->last_checked = time();
		$transient->checked[$this->getMainFile()] = $this->version;

		return $transient;
	}

	/**
	 * @inheritdoc
	 */
	public function checkTranslation($transient) : object
	{
		// Fix transient
		if ( !$this->isType('object', $transient) ) {
			$transient = new stdClass();
		}

		// Check translation API URL
		if ( !$this->translationUrl ) {
			return $transient;
		}

		// Fetch translation
		$update = $this->fetch('check-translation', $this->translationUrl);

		// Update transient
		if ( $this->isValid('translation', $update) ) {

			// Fix translation transient
			if ( !isset($transient->translations) ) {
				$transient = new stdClass();
				$transient->translations = [];
			}

			// Remove oldest translations
			foreach ($transient->translations as $key => $translation) {
				if ( $translation['slug'] == $this->getNameSpace() ) {
					unset($transient->translations[$key]);
				}
			}

			// Update translations
			foreach ($update->translations as $translation) {
				$transient->translations[] = $translation;
			}
		}

		return $transient;
	}

	/**
	 * @inheritdoc
	 */
	public function clearCache(object $upgrader, array $options)
	{
	    if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
	    	if ( isset($options['plugins']) ) {
		        foreach($options['plugins'] as $plugin) {
			        if ( $plugin == $this->getMainFile() ) {
						$this->deletePluginTransient($this->applyNamespace('get-info'));
						$this->deletePluginTransient($this->applyNamespace('check-update'));
						$this->deletePluginTransient($this->applyNamespace('check-translation'));
			        }
		        }
	    	}
	    }
	}

	/**
	 * @inheritdoc
	 */
	public function filterArgs(array $args) : array
	{
		if ( isset($args['reject_unsafe_urls']) ) {
			$args['reject_unsafe_urls'] = $this->hasSsl();
		}
		return $args;
	}

	/**
	 * @inheritdoc
	 */
	public function isUpdated() : bool
	{
		return (bool)$this->getPluginTransient('updated');
	}

	/**
	 * @inheritdoc
	 */
	public function setAsUpdated() : bool
	{
		return (bool)$this->setPluginTransient('updated', 1);
	}

	/**
	 * Fetch response (Info, Update, Translation),
	 * Used cached response by plugin version.
	 *  
	 * @access protected
	 * @param string $action
	 * @param string $url
	 * @return mixed
	 */
	protected function fetch(string $action, string $url)
	{
		$action = $this->applyNamespace($action);

		// Get updater response from cache
		$response = $this->getTransient(
			"{$action}-{$this->version}"
		);

		if ( !$response ) {

			// Set updater API request args
			$args = $this->mergeArray([
				'slug'      => $this->getNameSpace(),
				'version'   => $this->version,
				'wpversion' => $this->wpVersion,
				'ua'        => $this->getUserAgent(),
				'action'    => $action
			], $this->args);

			// Init updater API
			$api = new API();
			$api->setBaseUrl($url);
			$api->setHeaders($this->headers);
			$api->setArgs($this->maybeRequireSSL([
				'timeout'    => $this->getTimeout(),
				'sslverify'  => $this->hasSsl(),
				'user-agent' => $this->getUserAgent()
			]));

			// Set updater API request body (Including license)
			$body = ['request' => $this->serialize($args)];
			$body = $this->setLicense($body);
			$api->setBody($body);

			// Set updater API auth
			if ( $this->auth ) {
				if ( $this->isType('array', $this->auth) ) {
					$user = $this->auth[0] ?? '';
					$pswd = $this->auth[1] ?? '';
					$api->setBasicAuthentication($user, $pswd);

				} elseif ( $this->isType('string', $this->auth) ) {
					$api->setAuthentication($this->auth);
				}
			}

			// Send updater API request
			$api->send();

			// Cache on successful response
			if ( $api->getStatusCode() == 200 && ($body = $api->getBody()) ) {
				$response = $this->unserialize($body);
				$option = explode('-', $action);
				$option = $option[1] ?? 'default';
				$ttl = $this->applyPluginFilter("updater-{$option}-ttl", 1800);
				$this->setTransient("{$action}-{$this->version}", $response, $ttl);
			}
		}

		return $response;
	}

	/**
	 * Set updater license into request body.
	 *  
	 * @access protected
	 * @param array $body
	 * @return array
	 */
	protected function setLicense($body) : array
	{
		if ( $this->isType('array', $this->license) ) {
			foreach ($this->license as $arg => $value) {
				$body[$arg] = $value;
			}
		}
		return $body;
	}

	/**
	 * Get updater user-agent (UA).
	 *  
	 * @access protected
	 * @return string
	 */
	protected function getUserAgent() : string
	{
		return "{$this->applyNameSpace('wordpress')}/{$this->version};";
	}

	/**
	 * Get updater timeout.
	 *  
	 * @access protected
	 * @return int
	 */
	protected function getTimeout() : int
	{
		return (int)$this->applyPluginFilter('updater-timeout', 10);
	}

	/**
	 * Check updater API SSL.
	 *  
	 * @access protected
	 * @return bool
	 */
	protected function hasSsl() : bool
	{
		return $this->applyPluginFilter('updater-ssl', $this->isSsl());
	}

	/**
	 * Validate updater response object.
	 * 
	 * @access protected
	 * @param string $action
	 * @param mixed $response
	 * @return bool
	 */
	protected function isValid(string $action, $response) : bool
	{
		if ( $action == 'update' || $action == 'info' ) {
			if ( $this->isType('object', $response) && isset($response->plugin) ) {
				if ( $response->plugin == $this->getMainFile() ) {
					return true;
				}
			}

		} elseif ( $action == 'translation' ) {
			if ( $this->isType('object', $response) && isset($response->translations) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get updater default transient.
	 *  
	 * @access protected
	 * @param string $action
	 * @return object
	 */
	protected function getDefaultTransient(string $action) : object
	{
		$transient = new stdClass();
		$transient->name = $this->pluginHeader['Name'];
		$transient->slug = $this->getNameSpace();

		if ( $action == 'update' ) {
			$transient->id            = $this->slugify($this->pluginHeader['Name']);
			$transient->plugin        = $this->getMainFile();
			$transient->new_version   = $this->version;
			$transient->compatibility = new stdClass();

		} elseif ( $action == 'info' ) {
			
			$transient->homepage = $this->pluginHeader['PluginURI'];
			$transient->author   = $this->pluginHeader['AuthorName'];
			$transient->tested   = $this->wpVersion;
			$transient->requires = $this->pluginHeader['RequiresWP'];
		    $transient->sections = [
				'description' => $this->pluginHeader['Description']
		    ];
		    
			if ( $this->assetUrl ) {
				$this->assetUrl = rtrim((string)$this->assetUrl, '/');
				$transient->banners  = [
					'low'  => "{$this->assetUrl}/banner/{$this->applyNameSpace('772x250.png')}",
					'high' => "{$this->assetUrl}/banner/{$this->applyNameSpace('1544x500.png')}"
			    ];
			}
		}

		return $transient;
	}
}
