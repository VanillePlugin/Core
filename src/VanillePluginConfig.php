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

namespace VanillePlugin;

use VanillePlugin\exc\ConfigurationException;

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
		if ( isset($this->global) ) {
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

			$key = $this->applyPrefix('global');
			if ( !($this->global = $this->getTransient($key)) ) {
				$this->global = $this->parseConfig();
				$this->setTransient($key, $this->global, 0);
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
	 * @return bool
	 */
	protected function updateConfig(array $options = [], $args = 64|128|256) : bool
	{
		$json = $this->getRoot($this->config);
		$data = $this->parseJson($json, true);

		foreach ($options as $option => $value) {
			if ( isset($data['options'][$option]) ) {
				$data['options'][$option] = $value;
			}
		}

		$data['routes'] = (object)$data['routes'];
		$data['cron']   = (object)$data['cron'];
		$data['assets'] = (object)$data['assets'];
		$data = $this->formatJson($data, $args);

		return $this->writeFile($this->getRoot($this->config), $data);
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
		$config = $this->getConfig('path');
		$data = $config->asset ?? '/assets';
		return "/{$this->getNameSpace()}{$data}";
	}

	/**
	 * Get static assets url.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getAssetUrl() : string
	{
		$config = $this->getConfig('path');
		$data = $config->asset ?? '/assets';
		return "{$this->getBaseUrl()}{$data}";
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
		$config = $this->getConfig('path');
		$data = $config->asset ?? '/assets';
		$path = $this->getRoot($data);
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
		$config = $this->getConfig('path');
		$data = $config->migrate ?? '/migrate';
		$path = $this->getRoot($data);
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
		$config = $this->getConfig('path');
		$data = $config->cache ?? '/cache';
		return $this->getRoot($data);
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
		$config = $this->getConfig('path');
		$data = $config->temp ?? '/temp';
		$path = $this->getRoot($data);
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
		$config = $this->getConfig('options');
		$data = $config->ttl ?? 0;
		return (int)$data;
	}
	
	/**
	 * Get static view path.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getViewPath() : string
	{
		$config = $this->getConfig('path');
		$data = $config->view ?? '/view';
		return $this->getRoot($data);
	}

	/**
	 * Get static logs path.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getLoggerPath() : string
	{
		$config = $this->getConfig('path');
		$data = $config->logs ?? '/logs';
		return $this->getRoot($data);
	}

	/**
	 * Get static view extension.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getViewExtension() : string
	{
		$config = $this->getConfig('options');
		$data = $config->view->extension ?? '.html';
		return (string)$data;
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
		if ( !($data = $this->loadConfig('ajax')) ) {
			$data = $this->global->ajax ?? [];
		}
		return (object)$data;
	}

	/**
	 * Get Ajax admin actions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getAdminAjax() : array
	{
		$data = $this->getAjax()->admin ?? [];
		return (array)$data;
	}

	/**
	 * Get Ajax front actions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getFrontAjax() : array
	{
		$data = $this->getAjax()->front ?? [];
		return (array)$data;
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
		if ( !($data = $this->loadConfig('roles', true)) ) {
			$data = $this->global->roles ?? [];
		}
		return (array)$data;
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
		if ( !($data = $this->loadConfig('cron', true)) ) {
			$data = $this->global->cron ?? [];
		}
		return (array)$data;
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
		if ( !($data = $this->loadConfig('routes')) ) {
			$data = $this->global->routes ?? [];
		}
		return (object)$data;
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
		if ( !($data = $this->loadConfig('requirements')) ) {
			$data = $this->global->requirements ?? [];
		}
		return (object)$data;
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
		if ( !($data = $this->loadConfig('hooks', true)) ) {
			$data = $this->global->hooks ?? [];
		}
		return (array)$data;
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
		if ( !($data = $this->loadConfig('settings', true)) ) {
			$data = $this->global->settings ?? [];
		}
		return (array)$data;
	}

	/**
	 * Get group settings.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getGroupSettings() : array
	{
		$data = [];
		foreach ($this->getSettings() as $group) {
			$name = $group['group'];
			unset($group['group']);
			$data[$name][] = $group;
		}
		return (array)$data;
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
		if ( !($data = $this->loadConfig('assets', true)) ) {
			$data = $this->global->assets ?? [];
		}
		return (array)$data;
	}

	/**
	 * Get plugin remote assets.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getRemoteAssets() : array
	{
		$data = $this->getAssets()['remote'] ?? [];
		return (array)$data;
	}

	/**
	 * Get plugin local assets.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getLocalAssets() : array
	{
		$data = $this->getAssets()['local'] ?? [];
		return (array)$data;
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
		$data = $this->getLocalAssets()['admin'] ?? [];
		$data = $this->mapArray(function($item) use ($type, $env) {
			if ( $item['type'] === $type && $item['env'] === $env ) {
				return $item['path'];
			}
		}, $data);

		return $this->filterArray($data);
	}

	/**
	 * Get plugin front assets.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getFrontAssets(string $type, string $env = 'main') : array
	{
		$data = $this->getLocalAssets()['front'] ?? [];
		$data = $this->mapArray(function($item) use ($type, $env) {
			if ( $item['type'] === $type && $item['env'] === $env ) {
				return $item['path'];
			}
		}, $data);

		return $this->filterArray($data);
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
		if ( !($data = $this->loadConfig('strings', true)) ) {
			$data = $this->global->strings ?? [];
		}
		return (array)$data;
	}

	/**
	 * Get multiling status.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function isMultilingual() : bool
	{
		$config = $this->getConfig('options');
		$data = $config->multiling ?? false;
		return (bool)$data;
	}

	/**
	 * Get multisite status.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function allowedMultisite() : bool
	{
		$config = $this->getConfig('options');
		$data = $config->multisite ?? false;
		return (bool)$data;
	}

	/**
	 * Get plugin debug status.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function hasDebug() : bool
	{
		$config = $this->getConfig('options');
		$data = $config->debug ?? false;
		return (bool)$data;
	}

	/**
	 * Get static environment.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getEnv() : string
	{
		$config = $this->getConfig('options');
		$data = $config->environment ?? 'dev';
		return (string)$data;
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
			$key = $this->applyPrefix($config);
			if ( !($value = $this->getTransient($key)) ) {
				if ( $this->isFile( ($json = "{$dir}/{$config}.json") ) ) {
					$value = $this->decodeJson($this->readfile($json), $isArray);
				}
				$this->setTransient($key, $value, 0);
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
	 * Get plugin multiling status.
	 *
	 * @access public
	 * @return bool
	 */
	public function hasMultilingual() : bool
	{
		return ($this->isMultilingual() && $this->hasTranslator());
	}

	/**
	 * Get plugin current language (locale).
	 *
	 * @access protected
	 * @param bool $parse
	 * @return string
	 */
	protected function getLang(bool $parse = false) : string
	{
		// Get from system
		$lang = $default = $this->getLocale();

		// Get from third-party
		if ( $this->hasMultilingual() ) {
			$lang = $this->getTranslatorLocale();
		}

		// Get from default
		if ( !$lang ) {
			$lang = $default;
		}

		// Normalize
		$lang = $this->normalizeLocale($lang);
		return ($parse) ? $this->parseLang($lang) : $lang;
	}
}
