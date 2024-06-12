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

namespace VanillePlugin;

use VanillePlugin\exc\ConfigurationException;
use VanillePlugin\inc\Arrayify;

/**
 * Define base configuration used by plugin.
 *
 * - Configuration
 * - Translation
 * - Formatting
 * - Caching
 * - IO
 * 
 * @see https://developer.wordpress.org/plugins/
 */
trait VanillePluginConfig
{
	use \VanillePlugin\tr\TraitConfigurable,
		\VanillePlugin\tr\TraitTranslatable,
		\VanillePlugin\tr\TraitFormattable,
		\VanillePlugin\tr\TraitCacheable,
		\VanillePlugin\tr\TraitIO;

	/**
	 * @access private
	 * @var int $depth, Base path depth
	 * @var bool $cacheable, Config cache
	 * @var string $config, Config path
	 * @var object $global, Config global
	 */
	private $depth = 5;
	private $cacheable = true;
	private $config = '/core/storage/config/global.json';
	private $global;

	/**
	 * Init configuration.
	 */
	public function __construct()
	{
		$this->initConfig();
	}

	/**
	 * Prevent object clone.
	 */
    public function __clone()
    {
        die(__METHOD__ . ': Clone denied');
    }

	/**
	 * Prevent object serialization.
	 */
    public function __wakeup()
    {
        die(__METHOD__ . ': Unserialize denied');
    }

	/**
	 * Get static instance,
	 * Allows access to plugin config.
	 *
	 * @access protected
	 * @return object
	 */
	protected static function getStatic() : object
	{
		return new static;
	}
	
	/**
	 * Set plugin config.
	 *
	 * @access protected
	 * @return void
	 * @throws ConfigurationException
	 */
	protected function initConfig()
	{
		if ( $this->global ) {
			return;
		}
		
		// Override config
		if ( defined('VanillePluginDepth') ) {
			$this->depth = (int)constant('VanillePluginDepth');
		}
		if ( defined('VanillePluginConfigPath') ) {
			$this->config = (string)constant('VanillePluginConfigPath');
		}
		if ( defined('VanillePluginCache') ) {
			$this->cacheable = (bool)constant('VanillePluginCache');
		}

		if ( $this->cacheable ) {
			$config = "{$this->getNameSpace()}-global";
			if ( !$this->global = $this->getTransient($config) ) {
				$this->global = $this->parseConfig();
				$this->setTransient($config, $this->global, 0);
			}

		} else {
			$this->global = $this->parseConfig();
		}
	}
	
	/**
	 * Parse plugin configuration file.
	 *
	 * @access protected
	 * @return mixed
	 * @throws ConfigurationException
	 */
	protected function parseConfig()
	{
		$json = $this->getRoot($this->config);
		if ( $this->isFile($json) ) {

			$global = $this->parseJson($json);
			VanillePluginValidator::checkConfig($global, $json);
			return $global;

		} else {
	        throw new ConfigurationException(
	            ConfigurationException::invalidPluginConfigurationFile($json)
	        );
		}
	}

	/**
	 * Reset config objects.
	 *
	 * @access protected
	 * @return void
	 */
	protected function resetConfig()
	{
		unset($this->global);
	}

	/**
	 * Get global config option.
	 *
	 * @access protected
	 * @param string $key
	 * @return mixed
	 */
	protected function getConfig(?string $key = null)
	{
		$this->initConfig();
		if ( $key ) {
			return $this->global->{$key} ?? null;
		}
		return $this->global;
	}

	/**
	 * Update global custom options.
	 *
	 * @access protected
	 * @param array $options
	 * @param int $args
	 * @return void
	 */
	protected function updateConfig(array $options = [], $args = 64|128|256)
	{
		$json = $this->getRoot($this->config);
		$update = $this->parseJson($json, true);
		foreach ($options as $option => $value) {
			if ( isset($update['options'][$option]) ) {
				$update['options'][$option] = $value;
			}
		}
		$update['routes'] = (object)$update['routes'];
		$update['cron']   = (object)$update['cron'];
		$update['assets'] = (object)$update['assets'];
		$update = $this->formatJson($update, $args);
		$this->writeFile($this->getRoot($this->config), $update);
	}

	/**
	 * Set global config path.
	 *
	 * @access protected
	 * @param string $path
	 * @return void
	 */
	protected function setConfigPath($path = '/global.json')
	{
		$this->config = $path;
	}

	/**
	 * Get dynamic root.
	 *
	 * @access protected
	 * @param string $sub
	 * @return string
	 */
	protected function getRoot(?string $sub = null) : string
	{
		$path = __DIR__;
		for ($i=0; $i < $this->depth; $i++) {
			$path = dirname($path);
		}
		if ( $sub ) {
			$path .= "/{$sub}";
		}
		return $this->formatPath($path);
	}

	/**
	 * Get dynamic namespace.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getNameSpace() : string
	{
		return $this->slugify(
			$this->basename($this->getRoot())
		);
	}

	/**
	 * Get static name.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getPluginName() : string
	{
		return $this->getConfig('name');
	}

	/**
	 * Get static description.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getPluginDescription() : string
	{
		return $this->getConfig('description');
	}

	/**
	 * Get static author.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getPluginAuthor() : string
	{
		return $this->getConfig('author');
	}

	/**
	 * Get static link.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getPluginLink() : string
	{
		return $this->getConfig('link');
	}

	/**
	 * Get static version.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getPluginVersion() : string
	{
		return $this->getConfig('version');
	}

	/**
	 * Get static prefix.
	 *
	 * @access protected
	 * @param bool $sep, Separator
	 * @return string
	 */
	protected function getPrefix(bool $sep = true) : string
	{
		$prefix = $this->undash(
			$this->getNameSpace()
		);
		if ( $sep ) {
			$prefix = "{$prefix}_";
		}
		return $prefix;
	}

	/**
	 * Get static assets relative path.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getAsset() : string
	{
		$paths = $this->getConfig('path');
		$asset = $paths->asset ?? '/assets';
		return "/{$this->getNameSpace()}{$asset}";
	}

	/**
	 * Get static assets url.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getAssetUrl() : string
	{
		$paths = $this->getConfig('path');
		$asset = $paths->asset ?? '/assets';
		return "{$this->getBaseUrl()}{$asset}";
	}
	
	/**
	 * Get static assets path.
	 *
	 * @access protected
	 * @param string $sub
	 * @return string
	 */
	protected function getAssetPath(?string $sub = null) : string
	{
		$paths = $this->getConfig('path');
		$asset = $paths->asset ?? '/assets';
		$path = $this->getRoot($asset);
		if ( $sub ) {
			$path .= "/{$sub}";
		}
		return $this->formatPath($path);
	}

	/**
	 * Get static migrate path.
	 *
	 * @access protected
	 * @param string $sub
	 * @return string
	 */
	protected function getMigratePath(?string $sub = null) : string
	{
		$paths = $this->getConfig('path');
		$migrate = $paths->migrate ?? '/migrate';
		$path = $this->getRoot($migrate);
		if ( $sub ) {
			$path .= "/{$sub}";
		}
		return $this->formatPath($path);
	}

	/**
	 * Get static cache path.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getCachePath() : string
	{
		$paths = $this->getConfig('path');
		$cache = $paths->cache ?? '/cache';
		return $this->getRoot($cache);
	}

	/**
	 * Get static temp path.
	 *
	 * @access protected
	 * @param string $sub
	 * @return string
	 */
	protected function getTempPath(?string $sub = null) : string
	{
		$paths = $this->getConfig('path');
		$temp = $paths->temp ?? '/temp';
		$path = $this->getRoot($temp);
		if ( $sub ) {
			$path .= "/{$sub}";
		}
		return $this->formatPath($path);
	}

	/**
	 * Get static expire.
	 *
	 * @access protected
	 * @return int
	 */
	protected function getExpireIn() : int
	{
		$options = $this->getConfig('options');
		$ttl = $options->ttl ?? 0;
		return (int)$ttl;
	}
	
	/**
	 * Get static view path.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getViewPath() : string
	{
		$paths = $this->getConfig('path');
		$view = $paths->view ?? '/view';
		return $this->getRoot($view);
	}

	/**
	 * Get static logs path.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getLoggerPath() : string
	{
		$paths = $this->getConfig('path');
		$logs = $paths->logs ?? '/logs';
		return $this->getRoot($logs);
	}

	/**
	 * Get static view extension.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getViewExtension() : string
	{
		$options = $this->getConfig('options');
		$extension = $options->view->extension ?? '.html';
		return (string)$extension;
	}

	/**
	 * Get main filename.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getMainFile() : string
	{
		$namespace = $this->getNameSpace();
		return "{$namespace}/{$namespace}.php";
	}

	/**
	 * Get main file path.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getMainFilePath() : string
	{
		return $this->getRoot("{$this->getNameSpace()}.php");
	}

	/**
	 * Get static Base url.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getBaseUrl() : string
	{
		return $this->getPluginUrl("{$this->getNameSpace()}");
	}

	/**
	 * Get ajax actions.
	 *
	 * @access protected
	 * @return object
	 */
	protected function getAjax() : object
	{
		$this->initConfig();
		if ( !($ajax = $this->loadConfig('ajax')) ) {
			$ajax = $this->global->ajax ?? [];
		}
		return (object)$ajax;
	}

	/**
	 * Get Ajax admin actions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getAdminAjax() : array
	{
		$ajax = $this->getAjax()->admin ?? [];
		return (array)$ajax;
	}

	/**
	 * Get Ajax front actions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getFrontAjax() : array
	{
		$ajax = $this->getAjax()->front ?? [];
		return (array)$ajax;
	}

	/**
	 * Get plugin roles.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getPluginRoles() : array
	{
		$this->initConfig();
		if ( !($roles = $this->loadConfig('roles', true)) ) {
			$roles = $this->global->roles ?? [];
		}
		return (array)$roles;
	}

	/**
	 * Get cron actions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getCron() : array
	{
		$this->initConfig();
		if ( !($cron = $this->loadConfig('cron', true)) ) {
			$cron = $this->global->cron ?? [];
		}
		return (array)$cron;
	}

	/**
	 * Get api routes.
	 *
	 * @access protected
	 * @return object
	 */
	protected function getRoutes() : object
	{
		$this->initConfig();
		if ( !($routes = $this->loadConfig('routes')) ) {
			$routes = $this->global->routes ?? [];
		}
		return (object)$routes;
	}

	/**
	 * Get requirements.
	 *
	 * @access protected
	 * @return object
	 */
	protected function getRequirements() : object
	{
		$this->initConfig();
		if ( !($requirements = $this->loadConfig('requirements')) ) {
			$requirements = $this->global->requirements ?? [];
		}
		return (object)$requirements;
	}

	/**
	 * Get hooks.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getHooks() : array
	{
		$this->initConfig();
		if ( !($hooks = $this->loadConfig('hooks', true)) ) {
			$hooks = $this->global->hooks ?? [];
		}
		return (array)$hooks;
	}

	/**
	 * Get settings.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getSettings() : array
	{
		$this->initConfig();
		if ( !($settings = $this->loadConfig('settings', true)) ) {
			$settings = $this->global->settings ?? [];
		}
		return (array)$settings;
	}

	/**
	 * Get group settings.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getGroupSettings() : array
	{
		$groups = [];
		foreach ($this->getSettings() as $group) {
			$name = $group['group'];
			unset($group['group']);
			$groups[$name][] = $group;
		}
		return (array)$groups;
	}

	/**
	 * Get plugin assets.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getAssets() : array
	{
		$this->initConfig();
		if ( !($assets = $this->loadConfig('assets', true)) ) {
			$assets = $this->global->assets ?? [];
		}
		return (array)$assets;
	}

	/**
	 * Get plugin remote assets.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getRemoteAssets() : array
	{
		$assets = $this->getAssets();
		$remote = $assets['remote'] ?? [];
		return (array)$remote;
	}

	/**
	 * Get plugin local assets.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getLocalAssets() : array
	{
		$assets = $this->getAssets();
		$local = $assets['local'] ?? [];
		return (array)$local;
	}

	/**
	 * Get plugin admin assets.
	 *
	 * @access protected
	 * @param string $type
	 * @param string $env
	 * @return array
	 */
	protected function getAdminAssets(string $type, string $env = 'main') : array
	{
		$assets = $this->getLocalAssets()['admin'] ?? [];
		$assets = $this->mapArray(function($item) use ($type, $env) {
			if ( $item['type'] === $type && $item['env'] === $env ) {
				return $item['path'];
			}
		}, $assets);

		return Arrayify::filter($assets);
	}

	/**
	 * Get plugin front assets.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getFrontAssets(string $type, string $env = 'main') : array
	{
		$assets = $this->getLocalAssets()['front'] ?? [];
		$assets = $this->mapArray(function($item) use ($type, $env) {
			if ( $item['type'] === $type && $item['env'] === $env ) {
				return $item['path'];
			}
		}, $assets);

		return Arrayify::filter($assets);
	}

	/**
	 * Get strings.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getStrings() : array
	{
		$this->initConfig();
		if ( !($strings = $this->loadConfig('strings', true)) ) {
			$strings = $this->global->strings ?? [];
		}
		return (array)$strings;
	}

	/**
	 * Get multilingual status.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function isMultilingual() : bool
	{
		$options = $this->getConfig('options');
		$multilingual = $options->multilingual ?? false;
		return (bool)$multilingual;
	}

	/**
	 * Get multisite status.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function allowedMultisite() : bool
	{
		$options = $this->getConfig('options');
		$multisite = $options->multisite ?? false;
		return (bool)$multisite;
	}

	/**
	 * Get plugin debug status.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function hasDebug() : bool
	{
		$options = $this->getConfig('options');
		$debug = $options->debug ?? false;
		return (bool)$debug;
	}

	/**
	 * Get static environment.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getEnv() : string
	{
		$options = $this->getConfig('options');
		$env = $options->environment ?? 'dev';
		return (string)$env;
	}

	/**
	 * Load configuration file.
	 *
	 * @access protected
	 * @param string $config
	 * @param bool $isArray
	 * @return mixed
	 */
	protected function loadConfig(string $config, bool $isArray = false)
	{
		$value = false;
		$dir = dirname($this->getRoot($this->config));

		if ( $this->cacheable ) {
			$name = $this->applyNamespace($config);
			if ( !$value = $this->getTransient($name) ) {
				if ( $this->isFile( ($json = "{$dir}/{$config}.json") ) ) {
					$value = $this->decodeJson($this->readfile($json), $isArray);
				}
				$this->setTransient($name, $value, 0);
			}

		} else {
			if ( $this->isFile( ($json = "{$dir}/{$config}.json") ) ) {
				$value = $this->decodeJson($this->readfile($json), $isArray);
			}
		}

		return $value;
	}

	/**
	 * Apply plugin namespace.
	 *
	 * @access protected
	 * @param string $key
	 * @param string $sep
	 * @return string
	 */
	protected function applyNamespace(string $key, string $sep = '-') : string
	{
		return "{$this->getNameSpace()}{$sep}{$key}";
	}

	/**
	 * Apply plugin prefix.
	 *
	 * @access protected
	 * @param string $key
	 * @param bool $sep, Separator
	 * @return string
	 */
	protected function applyPrefix(string $key, bool $sep = true) : string
	{
		$key = $this->undash($key);
		return "{$this->getPrefix($sep)}{$key}";
	}

	/**
	 * Apply plugin sufix.
	 *
	 * @access protected
	 * @param string $key
	 * @param bool $sep, Separator
	 * @return string
	 */
	protected function applySufix(string $key, bool $sep = true) : string
	{
		$key = $this->undash($key);
		if ( $sep ) {
			$key = "{$key}_";
		}
		return "{$key}{$this->getPrefix(false)}";
	}

	/**
	 * Apply plugin asset path.
	 *
	 * @access protected
	 * @param string $path
	 * @return string
	 */
	protected function applyAsset(string $path) : string
	{
		return "{$this->getAsset()}{$path}";
	}

	/**
	 * Get plugin multilingual status.
	 *
	 * @access public
	 * @return bool
	 */
	public function hasMultilingual() : bool
	{
		return ($this->isMultilingual() && $this->hasTranslator());
	}

	/**
	 * Get plugin language.
	 *
	 * @access protected
	 * @param bool $country
	 * @return string
	 */
	protected function getLang(bool $country = true) : string
	{
		// Get from system
		$locale = $default = $this->getLocale();

		// Get from third-party
		if ( $this->hasMultilingual() ) {
			$locale = $this->getTranslatorLocale();
		}

		// Get from default
		if ( !$locale ) {
			$locale = $default;
		}

		// Convert to country code
		if ( $country ) {
			return $this->getTranslatorCountry($locale);
		}

		return $this->getLanguage($locale);
	}
}
