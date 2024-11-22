<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin;

use VanillePlugin\exc\ConfigException;

/**
 * Define plugin configuration.
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
	 * @var string $path, Config root path
	 * @var object $global, Config global
	 */
	private $depth = 5;
	private $cacheable = true;
	private $root = '/core/storage/config';
	private $global;

	/**
	 * Init config.
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
	 * Get static instance.
	 *
	 * @access protected
	 * @return object
	 */
	protected static function getStatic() : object
	{
		return new static;
	}
	
	/**
	 * Init plugin global config.
	 *
	 * @access protected
	 * @return void
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
			$this->root = (string)constant('VanillePluginConfigPath');
		}
		if ( defined('VanillePluginCache') ) {
			$this->cacheable = (bool)constant('VanillePluginCache');
		}

		if ( $this->cacheable ) {

			$key = $this->applyPrefix('global');
			if ( !($this->global = $this->getTransient($key)) ) {
				$this->global = $this->parseConfig('global');
				$this->setTransient($key, $this->global, 0);
			}

		} else {
			$this->global = $this->parseConfig('global');
		}
	}

	/**
	 * Parse plugin config file.
	 *
	 * @access protected
	 * @param string $config
	 * @param bool $validate
	 * @return mixed
	 * @throws ConfigException
	 */
	protected function parseConfig(string $config, bool $validate = true)
	{
		$file = $this->getRoot(
			"{$this->root}/{$config}.json"
		);

		if ( !$this->isFile($file) ) {
	        throw new ConfigException(
	            ConfigException::invalidConfigFile($file)
	        );
		}

		$data = $this->parseJson($file);
		if ( $validate ) {
			VanillePluginValidator::validate($data, $config);
		}

		return $data;
	}

	/**
	 * Reset config object.
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
			$data = $this->global->{$key} ?? null;

		} else {
			$data = $this->global;
		}
		$this->resetConfig();
		return $data;
	}

	/**
	 * Update global config options.
	 *
	 * @access protected
	 * @param array $options
	 * @param int $args
	 * @return bool
	 */
	protected function updateConfig(array $options = [], int $args = 64|256) : bool
	{
		if ( $this->getEnv() == 'dev' ) {
			return false;
		}

		if ( $this->hasDebug() ) {
			$args = 64|128|256;
		}

		$file = $this->getRoot(
			"{$this->root}/global.json"
		);
		$data = $this->parseJson($file);

		foreach ($options as $option => $value) {
			if ( isset($data['options'][$option]) ) {
				$data['options'][$option] = $value;
			}
		}

		VanillePluginValidator::validate($data, 'global');
		
		$data = $this->formatJson($data, $args);
		return $this->writeFile($file, $data);
	}

	/**
	 * Load partial config file.
	 *
	 * @access protected
	 * @param string $config
	 * @param bool $validate
	 * @return array
	 */
	protected function loadConfig(string $config, bool $validate = true) : array
	{
		$data = [];

		if ( $this->cacheable ) {

			$key = $this->applyPrefix($config);
			if ( !($data = $this->getTransient($key)) ) {
				$data = $this->parseConfig($config, $validate);
				$this->setTransient($key, $data, 0);
			}

		} else {
			$data = $this->parseConfig($config, $validate);
		}

		return $this->toArray($data);
	}

	/**
	 * Get dynamic root path.
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
	 * Get dynamic namespace by root.
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
	 * Get static TTL.
	 *
	 * @access protected
	 * @return int
	 */
	protected function getExpireIn() : int
	{
		$config = $this->getConfig('options');
		return$config->ttl;
	}

	/**
	 * Get static timeout.
	 *
	 * @access protected
	 * @return int
	 */
	protected function getTimeout() : int
	{
		$config = $this->getConfig('options');
		return $config->timeout;
	}

	/**
	 * Get static secret key.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getSecret() : string
	{
		$config = $this->getConfig('options');
		return $config->secret;
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
		return $config->view->extension;
	}

	/**
	 * Get plugin roles.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getPluginRoles() : array
	{
		$config = $this->getConfig('options');
		return $config->roles;
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
		return $config->multiling;
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
		return $config->multisite;
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
		return $config->debug;
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
		return $config->environment;
	}

	/**
	 * Get plugin remote server host.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getHost() : string
	{
		$config = $this->getConfig('options');
		if ( !($data = $config->remote->host) ) {
			$data = $this->getRemoteServer('host');
		}
		return $data;
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
		return $this->getRoot(
			"{$this->getNameSpace()}.php"
		);
	}

	/**
	 * Get static Base url.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getBaseUrl() : string
	{
		return $this->getPluginUrl(
			"{$this->getNameSpace()}"
		);
	}

	/**
	 * Get ajax actions.
	 *
	 * @access protected
	 * @param string $type
	 * @return array
	 */
	protected function getAjax(?string $type = null) : array
	{
		$this->initConfig();
		$data = $this->loadConfig('ajax');
		$this->resetConfig();
		return ($type) ? $data[$type] : $data;
	}

	/**
	 * Get Ajax admin actions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getAdminAjax() : array
	{
		return $this->getAjax('admin');
	}

	/**
	 * Get Ajax front actions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getFrontAjax() : array
	{
		return $this->getAjax('front');
	}

	/**
	 * Get cron data.
	 *
	 * @access protected
	 * @param string $type
	 * @return array
	 */
	protected function getCron(?string $type = null) : array
	{
		$this->initConfig();
		$data = $this->loadConfig('cron');
		$this->resetConfig();
		return ($type) ? $data[$type] : $data;
	}

	/**
	 * Get cron events.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getEvents() : array
	{
		return $this->getCron('events');
	}

	/**
	 * Get cron schedules.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getSchedules() : array
	{
		$data = [];
		foreach ($this->getCron('schedules') as $schedule) {
			$name = $schedule['name'];
			unset($schedule['name']);
			$data[$name] = $schedule;
		}
		return $data;
	}

	/**
	 * Get api routes.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getRoutes() : array
	{
		$this->initConfig();
		$data = $this->loadConfig('routes');
		$this->resetConfig();
		return $data['routes'];
	}

	/**
	 * Get requirements.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getRequirements() : array
	{
		$this->initConfig();
		$data = $this->loadConfig('requirements');
		$this->resetConfig();
		return $data;
	}

	/**
	 * Get settings inputs.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getInputs() : array
	{
		$this->initConfig();
		$data = $this->loadConfig('inputs');
		$this->resetConfig();
		return $data;
	}

	/**
	 * Get hooks.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getHooks() : array
	{
		$data = [];
		foreach ($this->getInputs() as $key => $value) {
			$active = $value['hook'] ?? false;
			if ( $active === true ) {
				$data[] = $key;
			}
		}
		return $data;
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
		$data = $this->loadConfig('settings');
		$this->resetConfig();
		return $data['settings'];
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
		foreach ($this->getSettings() as $option) {
			$data[$option['name']] = [
				'value' => $option['value'],
				'lang'  => $option['lang']
			];
		}
		return $data;
	}

	/**
	 * Get plugin assets.
	 *
	 * @access protected
	 * @param string $type
	 * @return array
	 */
	protected function getAssets(?string $type = null) : array
	{
		$this->initConfig();
		$data = $this->loadConfig('assets');
		$this->resetConfig();
		return ($type) ? $data[$type] : $data;
	}

	/**
	 * Get plugin remote assets.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getRemoteAssets() : array
	{
		$data = [];
		foreach ($this->getAssets('remote') as $asset) {
			$data[$asset['name']] = $asset['src'];
		}
		return $data;
	}

	/**
	 * Get plugin local assets.
	 *
	 * @access protected
	 * @param string $type
	 * @return array
	 */
	protected function getLocalAssets(?string $type = null) : array
	{
		$data = $this->getAssets('local');
		return ($type) ? $data[$type] : $data;
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
		$data = $this->getLocalAssets('admin');
		$data = $this->map(function($item) use ($type, $env) {
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
		$data = $this->getLocalAssets('front');
		$data = $this->map(function($item) use ($type, $env) {
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
	 * @param string $type
	 * @return array
	 */
	protected function getStrings(?string $type = null) : array
	{
		$this->initConfig();
		$data = $this->loadConfig('strings');
		$this->resetConfig();
		return ($type) ? $data[$type] : $data;
	}

	/**
	 * Get plugin remote server.
	 *
	 * @access protected
	 * @param string $var
	 * @return mixed
	 */
	protected function getRemoteServer(?string $var = null)
	{
		$this->initConfig();
		$data = $this->loadConfig('remote');
		$this->resetConfig();
		return ($var) ? $data[$var] : $data;
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
	 * @return string
	 */
	protected function getLang() : string
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
		return $this->normalizeLocale($lang);
	}
}
