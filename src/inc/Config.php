<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 * Allowed to edit for plugin customization
 */

namespace winamaz\core\system\includes;

use winamaz\core\system\libraries\interfaces\ConfigInterface;

class Config implements ConfigInterface
{
	/**
	 * @access public
	 */
	public $root = '/winamaz';
	public $baseUri;
	public $ajaxUrl;
	public $pluginName;
	public $namespace;
	public $prefix;
	public $path;
	public $installSql;
	public $uninstallSql;

	/**
	 * @access protected
	 */
	protected $file;
	protected static $global;

	/**
	 * {{inherit}}
	 *
	 * @param string|null $action
	 * @return void
	 */
	public function __construct()
	{
		self::init();
	}

	/**
	 * Config getter
	 *
	 * @param string|null $action
	 * @return void
	 */
	public function __get($property)
	{
		return $this->$property;
	}

	/**
	 * Config setter
	 *
	 * @param string $property, mixed $value
	 * @return void
	 */
	public function __set($property,$value)
	{
		$this->$property = $value;
		return $this;
	}

	/**
	 * get global option
	 *
	 * @param string $name
	 * @return void
	 */
	public static function get($name)
	{
		return static::$global->$name;
	}

	/**
	 * Update option
	 *
	 * @param array $var
	 * @return void
	 */
	public function update($var = [])
	{
		$json = File::read("{$this->root}/core/storage/config/global.json");
		$update = Json::decode($json);
		foreach ($var as $option => $value)
		{
			if ( isset($update['option'][$option]) ) {
				$update['option'][$option] = $value;
			}
		}
		$update = Json::format($update);
		File::write("{$this->root}/core/storage/config/global.json", $update);
	}

	/**
	 * init configuration
	 *
	 * @param void
	 * @return void
	 */
	private function init()
	{
		// set global variables
		$this->ajaxUrl = admin_url('admin-ajax.php');
		$this->baseUri = WP_PLUGIN_URL . $this->root;
		$this->root    = WP_PLUGIN_DIR . $this->root;
		$this->file    = File::read("{$this->root}/core/storage/config/global.json");

		static::$global   = Json::decode($this->file, true);
		$this->pluginName = static::$global->name;
		$this->namespace  = static::$global->namespace;
		$this->prefix     = static::$global->prefix;
		$this->path       = static::$global->path;

		// activate debug
		if ( static::$global->option->debug ) {
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
		}
	}
}
