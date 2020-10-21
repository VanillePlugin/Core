<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.3
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\inc\Template;
use VanillePlugin\inc\Json;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\Date;
use VanillePlugin\lib\PluginOptions;
use VanillePlugin\int\ViewInterface;

class View extends PluginOptions implements ViewInterface
{
    /**
     * @access private
     * @var array $callables
     */
    private $callables = false;

	/**
	 * Define custom callables
	 *
	 * @access public
     * @param array $callables
	 * @return void
	 */
	public function setCallables($callables = [])
	{
		$this->callables = $callables;
	}

    /**
     * Render view
     *
     * @access public
     * @param {inherit}
     * @return void
     */
    public function render($content = [], $template = 'default')
    {
        echo $this->assign($content, $template);
    }

	/**
	 * Aassign content to view
	 *
     * @access public
	 * @param array $content
     * @param string $template
	 * @return string
	 */
	public function assign($content = [], $template = 'default')
	{
        // Set View environment
        $env = Template::getEnvironment($this->getPath($template),[
            'cache' => $this->getCachePath(),
            'debug' => $this->isDebug()
        ]);

        // Set custom callables
        if ($this->callables) {
            foreach ($this->callables as $name => $callable) {
                $env->addFunction(Template::extend($name, $callable));
            }
        }
    
		// Add view global functions
        $env->addFunction(Template::extend('dump', function ($var){
            var_dump($var);
        }));
        $env->addFunction(Template::extend('settingsFields', function ($group){
            settings_fields($group);
        }));
        $env->addFunction(Template::extend('settingsSections', function ($group){
            do_settings_sections($group);
        }));
        $env->addFunction(Template::extend('submitButton', function (){
            submit_button();
        }));
        $env->addFunction(Template::extend('isLoggedIn', function (){
            return $this->isLoggedIn();
        }));
        $env->addFunction(Template::extend('isDebug', function (){
            return $this->isDebug();
        }));
        $env->addFunction(Template::extend('getConfig', function ($config){
            return $this->getConfig($config);
        }));
        $env->addFunction(Template::extend('getPluginOption', function ($option, $type = 'array'){
            return $this->getPluginOption($option, $type);
        }));
        $env->addFunction(Template::extend('getRoot', function (){
            return $this->getRoot();
        }));
        $env->addFunction(Template::extend('getBaseUri', function (){
            return $this->getBaseUri();
        }));
        $env->addFunction(Template::extend('getAssetUri', function (){
            return $this->getAssetUri();
        }));
        $env->addFunction(Template::extend('nonce', function ($name = null){
            return wp_create_nonce($name);
        }));
        $env->addFunction(Template::extend('translate', function ($string){
            return $this->translateString($string);
        }));
        $env->addFunction(Template::extend('JSONdecode', function ($json){
            return Json::decode($json);
        }));
        $env->addFunction(Template::extend('JSONencode', function ($array){
            return Json::encode($array);
        }));
        $env->addFunction(Template::extend('exit', function (){
            exit;
        }));
        $env->addFunction(Template::extend('serialize', function ($data){
            return Stringify::serialize($data);
        }));
        $env->addFunction(Template::extend('unserialize', function ($string){
            return Stringify::unserialize($string);
        }));
        $env->addFunction(Template::extend('formatDate', function ($date, $format = 'm/d/Y H:i:s', $to = 'd/m/Y H:i:s'){
            return Date::toString($date, $format, $to);
        }));
        $env->addFunction(Template::extend('hasFilter', function ($hook){
            return $this->hasFilter($hook);
        }));
        $env->addFunction(Template::extend('applyFilter', function ($hook, $value){
            return $this->applyFilter($hook,$value);
        }));
        $env->addFunction(Template::extend('doAction', function ($hook, $args = null){
            $this->doPluginAction($hook,$args);
        }));

        // Return rendered view
		$view = $env->load("{$template}{$this->getViewExtension()}");
		return $view->render($content);
	}

    /**
     * Get view path
     *
     * @access protected
     * @param string $template
     * @return string
     */
    protected function getPath($template)
    {
        // Set overriding path
        $override = "{$this->getThemeDir()}/{$this->getNameSpace()}/";
        if ( file_exists("{$override}{$template}{$this->getViewExtension()}") ) {
            return $override;
        }
        return $this->getViewPath();
    }
}
