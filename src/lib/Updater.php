<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\int\UpdaterInterface;
use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\inc\Arrayify;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\Server;
use VanillePlugin\inc\GlobalConst;
use VanillePlugin\inc\API;
use \stdClass;

/**
 * Wrapper Class for Self-Hosted Plugin.
 */
class Updater extends PluginOptions implements UpdaterInterface
{
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
	protected $license;
	protected $headers;
	protected $auth;
	protected $args;
	protected $pluginHeader;

	/**
	 * @param PluginNameSpaceInterface $plugin
	 * @param string $host
	 * @param array $args
	 *
	 * action : admin_init
	 */
	public function __construct(PluginNameSpaceInterface $plugin, $host, $args = [])
	{
		// Init plugin config
		$this->initConfig($plugin);

		// Parse plugin version
		$this->pluginHeader = $this->getPluginHeader($this->getMainFile());
		$this->version = !empty($this->pluginHeader['Version'])
		? $this->pluginHeader['Version'] : $this->getPluginVersion();

		// Init updater config
		$this->wpVersion = GlobalConst::version();
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
		 * Get plugin info.
		 * Filter : plugins_api
		 *
		 * @see getInfo@self
		 * @property priority 10
		 * @property count 3
		 */
		$this->addFilter('plugins_api', [$this,'getInfo'], 10, 3);

		/**
		 * Check plugin update.
		 * Filter : pre_set_site_transient_{$transient}
		 * Filter : site_transient_update_{$transient}
		 *
		 * @see checkUpdate@self
		 * @property priority 10
		 * @property count 1
		 */
		$this->addFilter('pre_set_site_transient_update_plugins', [$this,'checkUpdate']);

		/**
		 * Check plugin translation update.
		 * Filter : pre_set_site_transient_{$transient}
		 * Filter : site_transient_update_{$transient}
		 *
		 * @see checkTranslation@self
		 * @property priority 10
		 * @property count 1
		 */
		$this->addFilter('pre_set_site_transient_update_plugins', [$this,'checkTranslation']);
	}

	/**
	 * Get plugin info.
	 * 
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

		// Check plugin
		if ( $args->slug === $this->getNameSpace() ) {

			// Check info API URL
			if ( !$this->infoUrl ) {
				return $transient;
			}

			// Fetch info
			$info = $this->fetch('get-info',$this->infoUrl);

			// Update transient
			if ( $this->isValid('info',$info) ) {
				$transient = $info;

			} else {
				$transient = $this->getDefaultTransient('info');
			}
		}

		return $transient;
	}

	/**
	 * Check plugin update.
	 * 
	 * @access public
	 * @param object $transient
	 * @return object
	 */
	public function checkUpdate($transient)
	{
		// Fix transient
		if ( !TypeCheck::isObject($transient) ) {
			$transient = new stdClass();
		}

		// Fetch update
		$update = $this->fetch('check-update',$this->updateUrl);

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
	 * Check plugin translation update.
	 * 
	 * @access public
	 * @param object $transient
	 * @return object
	 */
	public function checkTranslation($transient)
	{
		// Fix transient
		if ( !TypeCheck::isObject($transient) ) {
			$transient = new stdClass();
		}

		// Check translation API URL
		if ( !$this->translationUrl ) {
			return $transient;
		}

		// Fetch translation
		$update = $this->fetch('check-translation',$this->translationUrl);

		// Update transient
		if ( $this->isValid('translation',$update) ) {

			// Set translations
			if ( !isset($transient->translations) ) {
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
	 * Fetch response (Info, Update, Translation),
	 * Used cached response by plugin version.
	 *  
	 * @access protected
	 * @param string $action
	 * @param string $url
	 * @return mixed
	 */
	protected function fetch($action, $url)
	{
		// Get updater response from cache
		$response = $this->getTransient(
			"{$action}-{$this->version}"
		);

		if ( !$response ) {

			// Set updater API request args
			$args = Arrayify::merge([
				'slug'      => $this->getNameSpace(),
				'version'   => $this->version,
				'wpversion' => $this->wpVersion,
				'ua'        => $this->getUserAgent(),
				'action'    => "{$this->getNameSpace()}-{$action}"
			],$this->args);

			// Init updater API
			$api = new API();
			$api->setBaseUrl($url);
			$api->setHeaders($this->headers);
			$api->setArgs(Server::maybeRequireSSL([
				'timeout'    => $this->getTimeout(),
				'sslverify'  => $this->isSSL(),
				'user-agent' => $this->getUserAgent()
			]));

			// Set updater API request body (Including license)
			$body = ['request' => Stringify::serialize($args)];
			$body = $this->setLicense($body);
			$api->setBody($body);

			// Set updater API auth
			if ( $this->auth ) {
				if ( TypeCheck::isArray($this->auth) ) {
					$user = $this->auth[0] ?? '';
					$pswd = $this->auth[1] ?? '';
					$api->setBasicAuthentication($user,$pswd);

				} elseif ( TypeCheck::isString($this->auth) ) {
					$api->setAuthentication($this->auth);
				}
			}

			// Send updater API request
			$api->send();

			// Cache on successful response
			if ( $api->getStatusCode() == 200 && ($body = $api->getBody()) ) {
				$response = unserialize($body);
				$option = explode('-',$action);
				$option = $option[1] ?? 'default';
				$ttl = $this->applyPluginFilter("updater-{$option}-ttl",1800);
				$this->setTransient("{$action}-{$this->version}",$response,$ttl);
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
	protected function setLicense($body)
	{
		if ( TypeCheck::isArray($this->license) ) {
			foreach ($this->license as $arg => $value) {
				$body[$arg] = $value;
			}
		}
		return $body;
	}

	/**
	 * Get updater user-agent (ua),
	 * "{slug}-wordpress/{version};"
	 *  
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getUserAgent()
	{
		return "{$this->getNameSpace()}-wordpress/{$this->version};";
	}

	/**
	 * Get updater timeout.
	 *  
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getTimeout()
	{
		return $this->applyPluginFilter('updater-timeout',10);
	}

	/**
	 * Check updater API SSL.
	 *  
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function isSSL()
	{
		return $this->applyPluginFilter(
			'updater-ssl',
			Server::isSSL()
		);
	}

	/**
	 * Validate updater response object.
	 * 
	 * @access protected
	 * @param string $action
	 * @param mixed $response
	 * @return bool
	 */
	protected function isValid($action, $response)
	{
		if ( $action == 'update' || $action == 'info' ) {
			if ( TypeCheck::isObject($response) && isset($response->plugin) ) {
				if ( $response->plugin == $this->getMainFile() ) {
					return true;
				}
			}
		} elseif ( $action == 'translation' ) {
			if ( TypeCheck::isObject($response) && isset($response->translations) ) {
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
	protected function getDefaultTransient($action)
	{
		$transient = new stdClass();
		$transient->name = $this->pluginHeader['Name'];
		$transient->slug = $this->getNameSpace();

		if ( $action == 'update' ) {
			$transient->id            = Stringify::slugify($this->pluginHeader['Name']);
			$transient->plugin        = $this->getMainFile();
			$transient->new_version   = $this->version;
			$transient->compatibility = new stdClass();

		} elseif ( $action == 'info' ) {
			$this->assetUrl = rtrim($this->assetUrl,'/');
			$transient->homepage = $this->pluginHeader['PluginURI'];
			$transient->author   = $this->pluginHeader['AuthorName'];
			$transient->tested   = $this->wpVersion;
			$transient->requires = $this->pluginHeader['RequiresWP'];
			$transient->banners  = [
				'low'  => "{$this->assetUrl}/banner/{$this->getNameSpace()}-772x250.png",
				'high' => "{$this->assetUrl}/banner/{$this->getNameSpace()}-1544x500.png"
		    ];
		    $transient->sections = [
				'description' => $this->pluginHeader['Description']
		    ];
		}
		return $transient;
	}
}
