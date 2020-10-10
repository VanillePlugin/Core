<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.6
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePluginTest\lib;

use VanillePluginTest\inc\TemplateTest;
use VanillePluginTest\inc\JsonTest;
use VanillePluginTest\inc\StringifyTest;
use VanillePluginTest\inc\DateTest;
use VanillePluginTest\lib\PluginOptionsTest;
use VanillePluginTest\int\ViewInterfaceTest;

class ViewTest extends PluginOptionsTest implements ViewInterfaceTest
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
        $env = TemplateTest::getEnvironment($this->getPath($template),[
            'cache' => $this->getCachePath(),
            'debug' => $this->isDebug()
        ]);

        // Set custom callables
        if ($this->callables) {
            foreach ($this->callables as $name => $callable) {
                $env->addFunction(TemplateTest::extend($name, $callable));
            }
        }
    
		// Add view global functions
        $env->addFunction(TemplateTest::extend('dump', function ($var){
            var_dump($var);
        }));
        $env->addFunction(TemplateTest::extend('settingsFields', function ($group){
            settings_fields($group);
        }));
        $env->addFunction(TemplateTest::extend('settingsSections', function ($group){
            do_settings_sections($group);
        }));
        $env->addFunction(TemplateTest::extend('submitButton', function (){
            submit_button();
        }));
        $env->addFunction(TemplateTest::extend('isLoggedIn', function (){
            return $this->isLoggedIn();
        }));
        $env->addFunction(TemplateTest::extend('isDebug', function (){
            return $this->isDebug();
        }));
        $env->addFunction(TemplateTest::extend('getConfig', function ($config){
            return $this->getConfig($config);
        }));
        $env->addFunction(TemplateTest::extend('getPluginOption', function ($option, $type = 'array'){
            return $this->getPluginOption($option, $type);
        }));
        $env->addFunction(TemplateTest::extend('getRoot', function (){
            return $this->getRoot();
        }));
        $env->addFunction(TemplateTest::extend('getBaseUri', function (){
            return $this->getBaseUri();
        }));
        $env->addFunction(TemplateTest::extend('getAssetUri', function (){
            return $this->getAssetUri();
        }));
        $env->addFunction(TemplateTest::extend('nonce', function ($name = null){
            return wp_create_nonce($name);
        }));
        $env->addFunction(TemplateTest::extend('translate', function ($string){
            return $this->translateString($string);
        }));
        $env->addFunction(TemplateTest::extend('JSONdecode', function ($json){
            return Json::decode($json);
        }));
        $env->addFunction(TemplateTest::extend('JSONencode', function ($array){
            return Json::encode($array);
        }));
        $env->addFunction(TemplateTest::extend('exit', function (){
            exit;
        }));
        $env->addFunction(TemplateTest::extend('serialize', function ($data){
            return Stringify::serialize($data);
        }));
        $env->addFunction(TemplateTest::extend('unserialize', function ($string){
            return Stringify::unserialize($string);
        }));
        $env->addFunction(TemplateTest::extend('formatDate', function ($date, $format = 'M/d/Y H:i:s', $to = 'd/m/Y H:i:s'){
            return Date::toString($date, $format, $to);
        }));
        $env->addFunction(TemplateTest::extend('hasFilter', function ($hook){
            return $this->hasFilter($hook);
        }));
        $env->addFunction(TemplateTest::extend('applyFilter', function ($hook, $value){
            return $this->applyFilter($hook,$value);
        }));
        $env->addFunction(TemplateTest::extend('doAction', function ($hook, $args = null){
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
