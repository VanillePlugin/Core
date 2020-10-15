<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.9
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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

trait VanilleConfig
{
	/**
	 * @access private
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
		// Define Internal Namespace
		$this->namespace = $plugin->getNameSpace();
		
		// Parse VanillePLugin Config file
		$json = new Json("{$this->getRoot()}{$this->path}");
		$this->global = $json->parse();
	}

	/**
	 * Get global
	 *
	 * @access public
	 * @param string $var null
	 * @return mixed
	 */
	public function getConfig($var = null)
	{
		if ($var) {
			return isset($this->global->$var)
			? $this->global->$var : false;
		}
		return $this->global;
	}

	/**
	 * Get static namespace
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getNameSpace()
	{
		return $this->namespace;
	}

	/**
	 * Get static namespace
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getPluginName()
	{
		return $this->global->name;
	}

	/**
	 * Get static namespace
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getPluginDescription()
	{
		return $this->global->description;
	}

	/**
	 * Get static namespace
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getPluginAuthor()
	{
		return $this->global->author;
	}

	/**
	 * Get static namespace
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getPluginVersion()
	{
		return $this->global->version;
	}

	/**
	 * Get static prefix
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getPrefix()
	{
		$prefix = Stringify::replace('-', '_', $this->getNameSpace());
		return "{$prefix}_";
	}

	/**
	 * Get static prefix
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getAssetUri()
	{
		return "{$this->getBaseUri()}{$this->global->path->asset}";
	}
	
	/**
	 * Get static prefix
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getAsset()
	{
		return "/{$this->getNameSpace()}{$this->global->path->asset}";
	}

	/**
	 * Get static migrate
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getMigrate()
	{
		return "{$this->getRoot()}{$this->global->path->migrate}";
	}

	/**
	 * Get static migrate
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getCachePath()
	{
		return "{$this->getRoot()}{$this->global->path->cache}";
	}

	/**
	 * Get static migrate
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getViewPath()
	{
		return "{$this->getRoot()}{$this->global->path->view}";
	}

	/**
	 * Get logs path
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getLoggerPath()
	{
		return "{$this->getRoot()}{$this->global->path->logs}";
	}

	/**
	 * Get static migrate
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getViewExtension()
	{
		return $this->global->view->extension;
	}

	/**
	 * Get static root
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getRoot()
	{
		return Stringify::formatPath( WP_PLUGIN_DIR . '/' . self::getNameSpace() );
	}

	/**
	 * Get static root
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getMainFile()
	{
		return self::getNameSpace() . '/' . self::getNameSpace() . '.php';
	}

	/**
	 * Get static Base Uri
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getBaseUri()
	{
		return WP_PLUGIN_URL . '/' . self::getNameSpace();
	}

	/**
	 * Get ajax Uri
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getAjaxUrl()
	{
		return admin_url('admin-ajax.php');
	}

	/**
	 * Get ajax Uri
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getAjax()
	{
		return $this->global->ajax;
	}

	/**
	 * Get Requirements
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getRequirement()
	{
		return $this->global->requirement;
	}

	/**
	 * Get Debug
	 *
	 * @access public
	 * @param void
	 * @return boolean
	 */
	public function isDebug()
	{
		return $this->global->options->debug;
	}

	/**
	 * Update Custom Options
	 *
	 * @access public
	 * @param array $options
	 * @param int $args
	 * @return void
	 */
	public function updateConfig($options = [], $args = 64|128|256)
	{
		$json = new Json("{$this->getRoot()}{$this->path}");
		$config = $json->parse(true);
		foreach ($options as $option => $value) {
			if ( isset($config['options'][$option]) ) {
				$config['options'][$option] = $value;
			}
		}
		$config = Json::format($config, $args);
		File::w("{$this->getRoot()}{$this->path}",$config);
	}
}
