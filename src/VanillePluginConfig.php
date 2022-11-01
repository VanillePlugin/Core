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

namespace VanillePlugin;

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Json;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\GlobalConst;

trait VanillePluginConfig
{
	/**
	 * @access private
	 * @var string $path
	 * @var object $global
	 * @var string $namespace
	 */
	private $path = '/core/storage/config/global.json';
	private $global = false;
	private $namespace = false;

	/**
	 * Get static instance.
	 *
	 * @access public
	 * @param void
	 * @return object
	 */
	public static function getStatic()
	{
		return new static;
	}
	
	/**
	 * Set configuration JSON File,
	 * Allows access to parent config.
	 *
	 * @access protected
	 * @param PluginNameSpaceInterface $plugin
	 * @return void
	 */
	protected function initConfig(PluginNameSpaceInterface $plugin)
	{
		// Check Namespace
		VanillePluginValidator::checkNamespace($plugin);

		// Define plugin internal namespace
		$this->namespace = Stringify::slugify($plugin->getNameSpace());

		// Parse plugin config
		$json = "{$this->getRoot()}{$this->path}";
		if ( File::exists($json) ) {

			$this->global = Json::parse($json);
			VanillePluginValidator::checkConfig($this->global);

		} else {

			// Parse VanillePLugin Default Config
			$json = dirname(__FILE__).'/config.default.json';
			$this->global = Json::parse($json);
		}
	}

	/**
	 * Update Custom Options.
	 *
	 * @access protected
	 * @param array $options
	 * @param int $args
	 * @return void
	 */
	protected function updateConfig($options = [], $args = 64|128|256)
	{
		$json = "{$this->getRoot()}{$this->path}";
		$update = Json::parse($json,true);
		foreach ($options as $option => $value) {
			if ( isset($update['options'][$option]) ) {
				$update['options'][$option] = $value;
			}
		}
		$update['routes'] = (object)$update['routes'];
		$update['assets'] = (object)$update['assets'];
		$update = Json::format($update,$args);
		File::w("{$this->getRoot()}{$this->path}",$update);
	}

	/**
	 * Get global.
	 *
	 * @access protected
	 * @param string $var
	 * @return mixed
	 */
	protected function getConfig($var = null)
	{
		if ( $var ) {
			return $this->global->$var ?? null;
		}
		return $this->global;
	}

	/**
	 * Set config path.
	 *
	 * @access protected
	 * @param string $path
	 * @return void
	 */
	protected function setConfigPath($path = '/global.json')
	{
		$this->path = $path;
	}

	/**
	 * Get static namespace.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getNameSpace()
	{
		return $this->namespace;
	}

	/**
	 * Get static namespace.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getPluginName()
	{
		return $this->global->name;
	}

	/**
	 * Get static description.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getPluginDescription()
	{
		return $this->global->description;
	}

	/**
	 * Get static namespace.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getPluginAuthor()
	{
		return $this->global->author;
	}

	/**
	 * Get static version.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getPluginVersion()
	{
		return $this->global->version;
	}

	/**
	 * Get static prefix.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getPrefix()
	{
		$prefix = Stringify::replace('-', '_', $this->getNameSpace());
		return "{$prefix}_";
	}

	/**
	 * Get static assets url.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getAssetUrl()
	{
		return "{$this->getBaseUrl()}{$this->global->path->asset}";
	}
	
	/**
	 * Get static assets path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getAsset()
	{
		return "/{$this->getNameSpace()}{$this->global->path->asset}";
	}

	/**
	 * Get static migrate path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getMigrate()
	{
		return "{$this->getRoot()}{$this->global->path->migrate}";
	}

	/**
	 * Get static cache path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getCachePath()
	{
		return "{$this->getRoot()}{$this->global->path->cache}";
	}

	/**
	 * Get static temp path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getTempPath()
	{
		return "{$this->getRoot()}{$this->global->path->temp}";
	}

	/**
	 * Get static expire.
	 *
	 * @access protected
	 * @param void
	 * @return int
	 */
	protected function getExpireIn()
	{
		return intval($this->global->options->ttl);
	}
	
	/**
	 * Get static view path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getViewPath()
	{
		return "{$this->getRoot()}{$this->global->path->view}";
	}

	/**
	 * Get static logs path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getLoggerPath()
	{
		return "{$this->getRoot()}{$this->global->path->logs}";
	}

	/**
	 * Get static view extension.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getViewExtension()
	{
		return $this->global->options->view->extension;
	}

	/**
	 * Get static root.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getRoot()
	{
		return GlobalConst::pluginDir("{$this->getNameSpace()}");
	}

	/**
	 * Get main filename.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getMainFile()
	{
		return  "{$this->getNameSpace()}/{$this->getNameSpace()}.php";
	}

	/**
	 * Get main file path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getMainFilePath()
	{
		return "{$this->getRoot()}/{$this->getNameSpace()}.php";
	}

	/**
	 * Get static Base url.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getBaseUrl()
	{
		return GlobalConst::pluginUrl("{$this->getNameSpace()}");
	}

	/**
	 * Get ajax url.
	 *
	 * @access protected
	 * @param string $scheme
	 * @return string
	 */
	protected function getAjaxUrl($scheme = 'admin')
	{
		return $this->getAdminUrl('admin-ajax.php', $scheme);
	}

	/**
	 * Get admin url.
	 *
	 * @access protected
	 * @param string $url
	 * @param string $scheme
	 * @return string
	 */
	protected function getAdminUrl($url = null, $scheme = 'admin')
	{
		return admin_url($url,$scheme);
	}

	/**
	 * Get ajax actions.
	 *
	 * @access protected
	 * @param void
	 * @return object
	 */
	protected function getAjax()
	{
		$ajax = $this->loadConfig('ajax');
		return ($ajax) ? $ajax : $this->global->ajax;
	}

	/**
	 * Get api routes.
	 *
	 * @access protected
	 * @param void
	 * @return object
	 */
	protected function getRoutes()
	{
		$routes = $this->loadConfig('routes');
		return ($routes) ? $routes : $this->global->routes;
	}

	/**
	 * Get requirements.
	 *
	 * @access protected
	 * @param void
	 * @return object
	 */
	protected function getRequirement()
	{
		$requirement = $this->loadConfig('requirement');
		return ($requirement) ? $requirement : $this->global->requirement;
	}
	
	/**
	 * Get remote assets.
	 *
	 * @access protected
	 * @param void
	 * @return array
	 */
	protected function getRemoteAsset()
	{
		$assets = $this->loadConfig('assets');
		return ($assets) ? (array)$assets : (array)$this->global->assets;
	}

	/**
	 * Get multilingual status.
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function isMultilingual()
	{
		return $this->global->options->multilingual;
	}

	/**
	 * Get multisite status.
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function allowedMultisite()
	{
		return $this->global->options->multisite;
	}

	/**
	 * Get debug status.
	 *
	 * @access protected
	 * @param bool $global
	 * @return bool
	 */
	protected function isDebug($global = false)
	{
		if ( $global ) {
			if ( $this->global->options->debug || GlobalConst::debug() ) {
				return true;
			}
		}
		return $this->global->options->debug;
	}

	/**
	 * Load configuration file.
	 *
	 * @access private
	 * @param string $config
	 * @return mixed
	 */
	private function loadConfig($config)
	{
		$dir = dirname("{$this->getRoot()}{$this->path}");
		if ( File::exists( ($json = "{$dir}/{$config}.json") ) ) {
			return Json::decode(File::r($json));
		}
		return false;
	}
}
