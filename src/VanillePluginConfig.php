<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
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
 * - IO
 * 
 * @see https://developer.wordpress.org/plugins/
 */
trait VanillePluginConfig
{
	use \VanillePlugin\tr\TraitConfigurable,
		\VanillePlugin\tr\TraitTranslatable,
		\VanillePlugin\tr\TraitFormattable,
		\VanillePlugin\tr\TraitIO;

	/**
	 * @access private
	 * @var int $depth, Base path depth
	 * @var string $config, Config path
	 * @var object $global, Config global
	 */
	private $depth = 5;
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
	 * Set configuration JSON File,
	 * Allows access to plugin config.
	 *
	 * @access protected
	 * @return void
	 * @throws ConfigurationException
	 */
	protected function initConfig()
	{
		// Override config
		if ( defined('VanillePluginDepth') ) {
			$this->depth = (int)constant('VanillePluginDepth');
		}
		if ( defined('VanillePluginConfigPath') ) {
			$this->config = (string)constant('VanillePluginConfigPath');
		}

		// Parse plugin config
		$json = $this->getRoot($this->config);
		if ( $this->isFile($json) ) {

			$this->global = $this->parseJson($json);
			VanillePluginValidator::checkConfig($this->global, $json);

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
		unset($this->routes);
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
	protected function updateConfig($options = [], $args = 64|128|256)
	{
		$json = $this->getRoot($this->config);
		$update = $this->parseJson($json, true);
		foreach ($options as $option => $value) {
			if ( isset($update['options'][$option]) ) {
				$update['options'][$option] = $value;
			}
		}
		$update['routes'] = (object)$update['routes'];
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
			basename($this->getRoot())
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
		return $this->global->name;
	}

	/**
	 * Get static description.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getPluginDescription() : string
	{
		return $this->global->description;
	}

	/**
	 * Get static author.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getPluginAuthor() : string
	{
		return $this->global->author;
	}

	/**
	 * Get static link.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getPluginLink() : string
	{
		return $this->global->link;
	}

	/**
	 * Get static version.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getPluginVersion() : string
	{
		return $this->global->version;
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
	 * Get static assets url.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getAssetUrl() : string
	{
		return "{$this->getBaseUrl()}{$this->global->path->asset}";
	}
	
	/**
	 * Get static assets relative path.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getAsset() : string
	{
		return "/{$this->getNameSpace()}{$this->global->path->asset}";
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
		$path = $this->getRoot($this->global->path->asset);
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
		$path = $this->getRoot($this->global->path->migrate);
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
		return $this->getRoot($this->global->path->cache);
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
		$path = $this->getRoot($this->global->path->temp);
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
		return (int)$this->global->options->ttl;
	}
	
	/**
	 * Get static view path.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getViewPath() : string
	{
		return $this->getRoot($this->global->path->view);
	}

	/**
	 * Get static logs path.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getLoggerPath() : string
	{
		return $this->getRoot($this->global->path->logs);
	}

	/**
	 * Get static view extension.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getViewExtension() : string
	{
		return $this->global->options->view->extension;
	}

	/**
	 * Get main filename.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getMainFile() : string
	{
		return "{$this->getNameSpace()}/{$this->getNameSpace()}.php";
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
		$ajax = $this->loadConfig('ajax');
		return ($ajax) ? $ajax : $this->global->ajax;
	}

	/**
	 * Get Ajax admin actions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getAdminAjax() : array
	{
		return $this->getAjax()->admin;
	}

	/**
	 * Get Ajax front actions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getFrontAjax() : array
	{
		return $this->getAjax()->front;
	}

	/**
	 * Get cron actions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getCron() : array
	{
		$cron = $this->loadConfig('cron', true);
		return ($cron) ? $cron : $this->global->cron;
	}

	/**
	 * Get api routes.
	 *
	 * @access protected
	 * @return object
	 */
	protected function getRoutes() : object
	{
		$routes = $this->loadConfig('routes');
		if ( !$routes ) {
			$routes = $this->global->routes;
		}
		return $routes;
	}

	/**
	 * Get requirements.
	 *
	 * @access protected
	 * @return object
	 */
	protected function getRequirements() : object
	{
		$requirements = $this->loadConfig('requirements');
		if ( !$requirements ) {
			$requirements = $this->global->requirements;
		}
		return $requirements;
	}
	
	/**
	 * Get remote assets.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getRemoteAssets() : array
	{
		$assets = $this->loadConfig('assets');
		if ( !$assets ) {
			$assets = $this->global->assets;
		}
		return (array)$assets;
	}

	/**
	 * Get strings.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getStrings() : array
	{
		$strings = $this->loadConfig('strings', true);
		if ( !$strings ) {
			$strings = $this->global->strings;
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
		return $this->global->options->multilingual;
	}

	/**
	 * Get multisite status.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function allowedMultisite() : bool
	{
		return $this->global->options->multisite;
	}

	/**
	 * Get plugin debug status.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function hasDebug() : bool
	{
		return $this->global->options->debug;
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
		$dir = dirname($this->getRoot($this->config));
		if ( $this->isFile( ($json = "{$dir}/{$config}.json") ) ) {
			return $this->decodeJson($this->readfile($json), $isArray);
		}
		return false;
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
