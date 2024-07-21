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

namespace VanillePlugin\lib;

use VanillePlugin\int\UpdaterInterface;

/**
 * Plugin update manager (self-hosted).
 */
class Updater extends API implements UpdaterInterface
{
    use \VanillePlugin\VanillePluginOption;

	/**
	 * @access protected
	 * @var string $host, Updater host
	 * @var string $version, Plugin version
	 * @var string $wp, Site version
	 * @var string $php, Site PHP version
	 * @var string $domain, Site domain
	 * @var string $slug, Plugin slug
	 * @var string $file, Plugin file
	 * @var array $url, Updater URLs
	 * @var array $plugin, Plugin header
	 */
	protected $host;
	protected $version;
	protected $wp;
	protected $php;
	protected $domain;
	protected $slug;
	protected $file;
	protected $urls = [];
	protected $plugin = [];

	/**
	 * @inheritdoc
	 */
	public function __construct(array $auth = [], array $urls = [])
	{
		if ( $this->setHost($this->getHost()) ) {

			// Set auth
			$remote = $this->getRemoteServer();
			$this->auth = $this->mergeArray([
				'token' => $remote['token'],
				'user'  => $remote['user'],
				'pswd'  => $remote['pswd'],
				'key'   => $remote['key']
			], $auth);

			// Set URLs
			$this->urls = $this->mergeArray([
				'update'    => '/',
				'info'      => '/plugin/info/',
				'translate' => '/plugin/translate/',
				'public'    => '/public/update/'
			], $urls);

			// Set environment
			$this->php     = phpversion() ?: 'na';
			$this->wp      = $this->getSiteVersion() ?: 'na';
			$this->slug    = $this->getNameSpace();
			$this->domain  = $this->geSiteDomain() ?: 'na';
			$this->file    = $this->getMainFile();
			$this->plugin  = $this->getPluginHeader($this->file);
			$this->version = $this->plugin['Version'] ?? false;
	
			if ( !$this->version ) {
				$this->version = $this->getPluginVersion() ?: 'na';
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function listen()
	{
		if ( $this->host ) {

			// Set updater TTL
			$this->addPluginFilter('updater-ttl', [$this, 'ttl'], 10, 2);
		
			// Get plugin info
			$this->addFilter('plugins-api', [$this, 'getInfo'], 10, 3);
		
			// Check plugin update
			$this->addFilter('update-plugins', [$this, 'checkUpdate']);
		
			// Check plugin translation
			$this->addFilter('update-plugins', [$this, 'checkTranslation']);
		
			// Clear plugin update cache
			$this->addAction('upgrade-complete', [$this, 'clearCache'], 10, 2);

		}
	}

	/**
	 * @inheritdoc
	 */
	public function setHost($host) : bool
	{
		$this->host = $this->untrailingSlash((string)$host);
		return ($this->host) ? true : false;
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
	 * @inheritdoc
	 */
	public function remove() : bool
	{
		$transient = new \stdClass();
		return $this->setSiteTransient('update-plugins', $transient, 0);
	}

	/**
	 * @inheritdoc
	 */
	public function getInfo($transient, $action, $args)
	{
		// Check action
		if ( $action !== 'plugin_information' ) {
			return false;
		}

		// Check plugin
		if ( $args->slug === $this->slug ) {

			// Check info URL
			if ( !$this->urls['info'] ) {
				return $transient;
			}

			// Fetch info
			$info = $this->fetch('info');

			// Update transient
			if ( $this->isValid('info', $info) ) {
				$transient = $info;

			} else {
				$transient = $this->getDefault('info');
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
			$transient = new \stdClass();
		}

		// Fetch update
		$update = $this->fetch('update');

		// Update transient
		if ( $this->isValid('update', $update) ) {
			$transient->response[$this->file] = $update;

		} else {
	        $transient->no_update[$this->file] = $this->getDefault('update');
		}

		// Check transient
		$transient->last_checked = time();
		$transient->checked[$this->file] = $this->version;

		return $transient;
	}

	/**
	 * @inheritdoc
	 */
	public function checkTranslation($transient) : object
	{
		// Fix transient
		if ( !$this->isType('object', $transient) ) {
			$transient = new \stdClass();
		}

		// Check translation URL
		if ( !$this->urls['translate'] ) {
			return $transient;
		}

		// Fetch translation
		$update = $this->fetch('translate');

		// Update transient
		if ( $this->isValid('translation', $update) ) {

			// Fix translation transient
			if ( !isset($transient->translations) ) {
				$transient = new \stdClass();
				$transient->translations = [];
			}

			// Remove oldest translations
			foreach ($transient->translations as $key => $translation) {
				if ( $translation['slug'] == $this->slug ) {
					unset($transient->translations[$key]);
					break;
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
	public function clearCache($upgrader, $options)
	{
	    if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
	    	if ( isset($options['plugins']) ) {
		        foreach ($options['plugins'] as $plugin) {
			        if ( $plugin == $this->file ) {
						$this->removePluginTransients();
						$this->purgePluginCache();
						break;
			        }
		        }
	    	}
	    }
	}

	/**
	 * @inheritdoc
	 */
	public function ttl($ttl, $action) : int
	{
		if ( $action == 'info' ) {
			$ttl = 0;

		} elseif ( $action == 'translate' ) {
			$ttl = ($ttl + 300);
		}
		return abs($ttl);
	}

	/**
	 * @inheritdoc
	 */
	public function timeout() : int
	{
		$timeout = parent::timeout();
		return $this->applyPluginFilter('updater-timeout', $timeout);
	}

	/**
	 * Fetch updater response.
	 * [Filter: {plugin}-updater-ttl].
	 *
	 * @access protected
	 * @param string $action
	 * @return mixed
	 */
	protected function fetch(string $action)
	{
		$key = "updater-{$action}";
		$response = $this->getPluginTransient($key);
		$response = false; // TODO

		if ( !$response ) {

			parent::__construct(self::GET, [
				'timeout' => $this->timeout()
			]);

			$this->setBaseUrl($this->host);
			$this->setBody(
				$this->generateBody($action)
			);
			
			$url = $this->urls[$action] ?? 'update';
			$response = $this->send($url)->response();

			if ( $this->isType('string', $response) ) {
				$response = $this->unserialize($response);
			}

			$ttl = $this->applyPluginFilter('updater-ttl', 1800, $action);
			$this->setPluginTransient($key, $response, $ttl);

		}

		return $response;
	}

	/**
	 * Get updater default transient.
	 *
	 * @access protected
	 * @param string $action
	 * @return object
	 */
	protected function getDefault(string $action) : object
	{
		$transient = new \stdClass();
		$transient->name = $this->plugin['Name'];
		$transient->slug = $this->slug;

		if ( $action == 'update' ) {
			$transient->id            = $this->slug;
			$transient->plugin        = $this->file;
			$transient->new_version   = $this->version;
			$transient->compatibility = new \stdClass();

		} elseif ( $action == 'info' ) {
			
			$transient->homepage = $this->plugin['PluginURI'];
			$transient->author   = $this->plugin['AuthorName'];
			$transient->requires = $this->plugin['RequiresWP'];
			$transient->tested   = $this->wp;
		    $transient->sections = [
				'description' => $this->plugin['Description']
		    ];
		    
			if ( ($url = $this->urls['public']) ) {

				$url = $this->untrailingSlash((string)$url);
				if ( !$this->isType('url', $url) ) {
					$url = "{$this->host}/{$url}";
				}
				
				$transient->banners = [
					'low'  => "{$url}/banner/{$this->slug}-772x250.png",
					'high' => "{$url}/banner/{$this->slug}-1544x500.png}"
			    ];

			}
		}

		return $transient;
	}

	/**
	 * Generate updater body.
	 *
	 * @access protected
	 * @param string $action
	 * @return array
	 */
	protected function generateBody(string $action) : array
	{
		$data = $this->serialize([
			'slug'    => $this->slug,
			'version' => $this->version,
			'wp'      => $this->wp,
			'php'     => $this->php,
			'action'  => $action
		]);
		$body = ['request' => $data];

		return $this->mergeArray([
			'key'    => $this->auth['key'],
			'domain' => $this->domain
		], $body);
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
				if ( $response->plugin == $this->file ) {
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
}
