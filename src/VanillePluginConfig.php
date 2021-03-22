<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.5.0
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

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
	 * Get static instance
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
	 * Set Config Json File
	 * Allow Parent Config Access
	 *
	 * @access protected
	 * @param PluginNameSpaceInterface $plugin
	 * @return void
	 */
	protected function initConfig(PluginNameSpaceInterface $plugin)
	{
		// Check Namespace
		VanillePluginValidator::checkNamespace($plugin);

		// Define Plugin Internal Namespace
		$this->namespace = $plugin->getNameSpace();

		// Parse Plugin Config
		$config = "{$this->getRoot()}{$this->path}";
		if ( File::exists($config) ) {

			$json = new Json($config);
			VanillePluginValidator::checkConfig($json);
			$this->global = $json->parse();

		} else {

			// Parse VanillePLugin Default Config
			$json = new Json(dirname(__FILE__).'/config.default.json');
			$this->global = $json->parse();
		}
	}

	/**
	 * Update Custom Options
	 *
	 * @access protected
	 * @param array $options
	 * @param int $args
	 * @return void
	 */
	protected function updateConfig($options = [], $args = 64|128|256)
	{
		$config = new Json("{$this->getRoot()}{$this->path}");
		$update = $config->parse(true);
		foreach ($options as $option => $value) {
			if ( isset($update['options'][$option]) ) {
				$update['options'][$option] = $value;
			}
		}
		$update['routes'] = (object)$update['routes'];
		$update = Json::format($update,$args);
		File::w("{$this->getRoot()}{$this->path}",$update);
	}

	/**
	 * Get global
	 *
	 * @access protected
	 * @param string $var null
	 * @return mixed
	 */
	protected function getConfig($var = null)
	{
		if ($var) {
			return isset($this->global->$var)
			? $this->global->$var : false;
		}
		return $this->global;
	}

	/**
	 * Set config path
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
	 * Get static namespace
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
	 * Get static namespace
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
	 * Get static description
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
	 * Get static namespace
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
	 * Get static version
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
	 * Get static prefix
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
	 * Get static assets url
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
	 * Get static assets path
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
	 * Get static migrate path
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
	 * Get static cache path
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
	 * Get static temp path
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
	 * Get static expire
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
	 * Get static view path
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
	 * Get static logs path
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
	 * Get static view extension
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
	 * Get static root
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
	 * Get main filename
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
	 * Get main file path
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
	 * Get static Base url
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
	 * Get ajax url
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
	 * Get admin url
	 *
	 * @access protected
	 * @param string $url null
	 * @param string $scheme
	 * @return string
	 */
	protected function getAdminUrl($url = null, $scheme = 'admin')
	{
		return admin_url($url,$scheme);
	}

	/**
	 * Get ajax actions
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getAjax()
	{
		return $this->global->ajax;
	}

	/**
	 * Get api routes
	 *
	 * @access protected
	 * @param void
	 * @return object
	 */
	protected function getRoutes()
	{
		return $this->global->routes;
	}

	/**
	 * Get requirements
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getRequirement()
	{
		return $this->global->requirement;
	}

	/**
	 * Get multilingual status
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
	 * Get debug status
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
}
