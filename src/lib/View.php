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

namespace VanillePlugin\lib;

use VanillePlugin\int\ViewInterface;

/**
 * Plugin view controller.
 *
 * - Hooking
 * - Rendering
 * - Authentication
 * - Configuration
 * - Translation
 * - Formatting
 * - IO
 * - Caching
 * - Requesting
 * - Viewing
 * - Throwing
 */
class View implements ViewInterface
{
    use \VanillePlugin\VanillePluginOption,
        \VanillePlugin\tr\TraitViewable,
        \VanillePlugin\tr\TraitThrowable;

    /**
     * @access private
     * @var array $callables
     */
    private $callables = [];

	/**
	 * @inheritdoc
	 */
	public function setCallables(array $callables = [])
	{
		$this->callables = $this->mergeArray(
            $this->getDefaultCallables(),
            $callables
        );
	}

    /**
     * @inheritdoc
     */
    public function render(string $tpl = 'default', array $content = [], bool $end = false)
    {
        echo $this->assign($tpl, $content);
        if ( $end ) {
            die;
        }
    }

	/**
	 * @inheritdoc
	 */
	public function assign(string $tpl = 'default', array $content = [])
	{
        // Get View environment
        $env = $this->getEnvironment($this->getPath($tpl), [
            'cache' => $this->getCachePath(),
            'debug' => $this->hasDebug()
        ]);

        // Set callables
        if ( !$this->callables ) {
            $this->setCallables();
        }

        // Load callables
        foreach ($this->callables as $name => $callable) {
            $env->addFunction($this->extend($name, $callable));
        }

        // Return rendered view
        try {
            $view = $env->load("{$tpl}{$this->getViewExtension()}");
            return $view->render($content);

        } catch (\Exception $e) {
            if ( $this->hasDebug() ) {
                die($e);
            }
            $this->clearLastError();
        }

        return false;
	}

    /**
     * Get default callables.
     *
     * @access protected
     * @return array
     */
    protected function getDefaultCallables() : array
    {
		return [
			'dump' => function($var) {
                var_dump($var);
            },
			'exit' => function(?int $status = null) {
                exit($status);
            },
			'getSession' => function(?string $key = null) {
                return $this->getSession($key);
            },
			'settingsFields' => function(string $group) {
                $this->doSettingsFields($group);
            },
			'settingsSections' => function(string $page) {
                $this->doSettingsSections($page);
            },
			'submitButton' => function(?string $text = null) {
                $this->doSettingsSubmit($text);
            },
			'isLoggedIn' => function() : bool {
                return $this->isLoggedIn();
            },
			'hasDebug' => function() : bool {
                return $this->hasDebug();
            },
			'isDebug' => function() : bool {
                return $this->isDebug();
            },
			'getConfig' => function($key) {
                return $this->getConfig($key);
            },
			'getRoot' => function() : string {
                return $this->getRoot();
            },
			'getNameSpace' => function() : string {
                return $this->getNameSpace();
            },
			'getBaseUrl' => function() : string {
                return $this->getBaseUrl();
            },
			'getAssetUrl' => function() : string {
                return $this->getAssetUrl();
            },
			'nonce' => function($action = -1) : string {
                return $this->createToken($action);
            },
			'translate' => function(string $string) : string {
                return $this->trans($string);
            },
			'unJson' => function(string $value, bool $isArray = false) {
                return $this->decodeJson($value, $isArray);
            },
			'toJson' => function($value) {
                return $this->encodeJson($value);
            },
			'serialize' => function($value) {
                return $this->serialize($value);
            },
			'unserialize' => function(string $value) {
                return $this->unserialize($value);
            },
			'limitString' => function(string $string, int $limit) {
                return $this->limitString($string, $limit);
            },
			'getOption' => function(string $key, $default = false) {
                return $this->getOption($key, $default);
            },
			'getPluginOption' => function($k, $t = 'array', $d = false, $l = null) {
                return $this->getPluginOption($k, $t, $d, $l);
            },
			'hasFilter' => function(string $hook, $callback = false) {
                return $this->hasPluginFilter($hook, $callback);
            },
			'applyFilter' => function(string $hook, $value, $args = null) {
                return $this->applyPluginFilter($hook, $value, $args);
            },
			'hasAction' => function(string $hook, $callback = false) {
                return $this->hasPluginAction($hook, $callback);
            },
			'doAction' => function(string $hook, $args = null) {
                $this->doPluginAction($hook, $args);
            }
        ];
    }

    /**
     * Get view path (Overridden),
     * [Filter: {plugin}-template-path].
     *
     * @access private
     * @param string $tpl
     * @return string
     */
    private function getPath(string $tpl) : string
    {
        $path = $this->getThemeDir($this->getNameSpace());
        $path = $this->applyPluginFilter('template-path', $path);
        $file = "{$path}{$tpl}{$this->getViewExtension()}";
        if ( $this->isFile($file) ) {
            return $path;
        }
        return $this->getViewPath();
    }
}
