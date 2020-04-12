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

use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Environment;
use Twig_SimpleFunction as WPFunction;

class View
{
	/**
	 * @access protected
	 */
	public static $config;
	public static $extension = '.tpl';

	/**
	 * Assign data to view
	 *
	 * @param array $data, string $view
	 * @return {inherit}
	 */
	public static function render($data = [], $view = 'default')
	{
		echo self::assign($data, $view);
	}

	/**
	 * Render view
	 *
	 * @param array $data, string $view
	 * @return mixed
	 */
	public static function assign($data, $view)
	{
		static::$config = new Config;

		// set Loader path (overriding)
		$overPath = get_template_directory() . '/'. static::$config->namespace . '/';
		$overView = $overPath . $view . static::$extension;
		
		if ( file_exists($overView) ) $path = $overPath;
		else $path = WP_PLUGIN_DIR . static::$config->get('path')->view;

		// set Environment cache path
		$cache = static::$config->get('option')->cache;
		if ($cache) $cache = WP_PLUGIN_DIR . $cache;

		// set Environment debug
		$debug = static::$config->get('option')->debug;

		// set Environment settings
		$settings = [
		    'cache' => $cache,
		    'debug' => $debug
		];

		$loader = new Loader($path);

		// set View environment
		$environment = new Environment($loader, $settings);

		// add default functions
        $environment->addFunction(
        	new WPFunction('dump', function ($var){
            	var_dump($var);
        	}
    	));

		// add WordPress functions
        $environment->addFunction(
        	new WPFunction('settingsFields', function ($group){
            	settings_fields($group);
        	}
    	));

        $environment->addFunction(
        	new WPFunction('doSettingsSections', function ($group){
            	do_settings_sections($group);
        	}
    	));

        $environment->addFunction(
        	new WPFunction('getOption', function ($name){
            	return maybe_unserialize( get_option($name) );
        	}
        ));

        $environment->addFunction(
        	new WPFunction('submit', function (){
            	submit_button();
        	}
        ));

        $environment->addFunction(
        	new WPFunction('nonce', function ($name = null){
            	return wp_create_nonce($name);
        	}
        ));

        $environment->addFunction(
        	new WPFunction('translate', function ($string){
            	return __($string, static::$config->namespace);
        	}
        ));

        $environment->addFunction(
        	new WPFunction('getBaseUri', function (){
            	return static::$config->baseUri;
        	}
        ));

        $environment->addFunction(
        	new WPFunction('doAction', function ($action){
            	do_action( static::$config->namespace . "-{$action}" );
        	}
    	));

        $environment->addFunction(
        	new WPFunction('JSONdecode', function ($json){
            	return json_decode($json);
        	}
    	));

        $environment->addFunction(
            new WPFunction('money', function ($price){
                return number_format($price,2,'.','');
            }
        ));

        // return rendered view
		$view = $environment->load($view . static::$extension);
		return $view->render($data);
	}
}
