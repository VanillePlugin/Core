<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.5
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\int\RequirementInterface;
use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\Arrayify;
use VanillePlugin\inc\TypeCheck;

class Requirement extends Notice implements RequirementInterface
{
	/**
	 * @access private
	 * @var string $tpl
	 * @var array $strings
	 */
	private $tpl;
	private $strings;

	/**
	 * @param PluginNameSpaceInterface $plugin
	 */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);
		$this->init([$this,'requirePath']);
		$this->init([$this,'requirePlugins']);
		$this->init([$this,'requireOptions']);
		$this->init([$this,'requireTemplates']);
		$this->init([$this,'requireModules']);
		$this->init([$this,'php']);

		// Set template
		$this->tpl = $this->applyPluginFilter('requirement-template','admin/inc/notice/requirement');
		
		// Set strings
		$this->strings = $this->applyPluginFilter('requirement-strings',[
			'path'     => [
				'exists'   => '%1$s requires path \'%2$s\''
			],
			'plugin'   => [
				'install'  => 'Required, Please install it',
				'activate' => 'Required, Please activate it'
			],
			'option'   => [
				'missing' => 'Option Required',
				'empty'   => 'Option Not Defined'
			],
			'template' => [
				'single'   => 'Template Required',
				'multiple' => 'One Of Templates Required'
			],
			'module'   => [
				'required' => 'Required on server, Please activate it',
				'config'   => 'Required on server, Please activate it, Otherwise set \'%1$s\' to \'%2$s\''
			],
			'php'      => [
				'required' => 'Required'
			]
		]);
	}
	
	/**
	 * Check plugin paths.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function requirePath()
	{
		// Get required paths
		$paths = [];
		if ( $this->getCachePath() ) {
			$paths[] = $this->getCachePath();
		}
		if ( $this->getTempPath() ) {
			$paths[] = $this->getTempPath();
		}
		if ( $this->getLoggerPath() ) {
			$paths[] = $this->getLoggerPath();
		}

		foreach ($paths as $path) {

			// Check path
			if ( !File::isDir($path) || !File::isWritable($path) ) {

		        // Try creating path
				if ( !File::addDir(Stringify::formatPath($path)) ) {

					$message = $this->translateVars(
						$this->strings['path']['exists'],
						[
							$this->getPluginName(),
							basename($path)
						]
					);

					$notice  = '<div class="';
					$notice .= $this->getNameSpace();
					$notice .= '-notice notice notice-error">';
					$notice .= '<p>';
					$notice .= '<i class="icon-close"></i> ';
					$notice .= '<strong>';
					$notice .= $this->translateString('Warning') . ' : ';
					$notice .= '</strong>';
					$notice .= $message;
					$notice .= '</p>';
					$notice .= '<small>';
					$notice .= $path;
					$notice .= '</small>';
					$notice .= '</div>';

					echo $notice;
				}

			}

		}
	}
	
	/**
	 * Requires plugins.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function requirePlugins()
	{
		// Check plugins
		if ( !($plugins = $this->getConfig()->requirement->plugins) ) {
			return;
		}

		// Requires plugins
		foreach (Arrayify::uniqueMultiple($plugins) as $plugin) {

			$name = isset($plugin->name) ? $plugin->name : $plugin->slug;
			if ( !$this->isInstalled($plugin->slug) ) {
				
				$this->render([
					'item'   => $name,
					'notice' => $this->translateString($this->strings['plugin']['install']) . '.'
				],$this->tpl);

			} else {

				$callable = isset($plugin->callable) ? $plugin->callable : $plugin->slug;
				if ( !$this->isActivated($callable) ) {
					$this->render([
						'item'   => $name,
						'notice' => $this->translateString($this->strings['plugin']['activate']) . '.'
					],$this->tpl);
				}
			}
		}
	}

	/**
	 * Requires options.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function requireOptions()
	{
		// Check options
		if ( !($options = $this->getConfig()->requirement->options) ) {
			return;
		}

		// Requires options
		foreach (Arrayify::uniqueMultiple($options) as $option) {
			$name = isset($option->name) ? $option->name : $option->slug;
			if ( $this->getOption($option->slug) !== $option->value ) {
				$this->render([
					'item'   => $name,
					'notice' => $this->translateString($this->strings['option']['missing']) . '.'
				],$this->tpl);

			} elseif ( empty($this->getOption($option->slug)) ) {
				$this->render([
					'item'   => $name,
					'notice' => $this->translateString($this->strings['option']['empty']) . '.'
				],$this->tpl);
			}
		}
	}

	/**
	 * Requires templates.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function requireTemplates()
	{
		// Check templates
		if ( !($templates = $this->getConfig()->requirement->templates) ) {
			return;
		}

		// Requires templates
		$slugs = [];
		$names = [];
		foreach (Arrayify::uniqueMultiple($templates) as $template) {
			$slugs[] = $template->slug;
			$names[] = isset($template->name) ? $template->name : $template->slug;
		}

		if ( !Stringify::contains($slugs, $this->getOption('template')) ) {
			if ( count($slugs) > 1 ) {

				// Check for multiple templates
				$item = implode(', ',$names);
				$notice = $this->translateString($this->strings['template']['multiple']) . '.';

			} else {
				// Check for single template
				$item = trim(implode('',$names));
				$notice = $this->translateString($this->strings['template']['single']) . '.';
			}

			$this->render([
				'item'   => $item,
				'notice' => $notice,
			],$this->tpl);
		}
	}

	/**
	 * Requires modules.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function requireModules()
	{
		// Check modules
		if ( !($modules = $this->getConfig()->requirement->modules) ) {
			return;
		}

		// Requires modules
		foreach (Arrayify::uniqueMultiple($modules) as $module) {

			if ( isset($module->override) ) {

				// Overrided module check
				$name = $module->override->name ?? '';
				$value = $module->override->value ?? '';

				if ( !$this->isActivated($module->callable) && !$this->hasConfig($name,$value) ) {
					$notice = $this->translateVars(
						$this->strings['module']['config'],
						[$name,$value]
					) . '.';
					$this->render([
						'item'   => $module->name,
						'notice' => $notice
					],$this->tpl);
				}

			} else {
				
				// Single module check
				if ( !$this->isActivated($module->callable) ) {
					$this->render([
						'item'   => $module->name,
						'notice' => $this->translateString($this->strings['module']['required']) . '.'
					],$this->tpl);
				}
			}
		}
	}

	/**
	 * Requires PHP version.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function php()
	{
		// Check version
		if ( !($version = $this->getConfig()->requirement->php) ) {
			return;
		}

		if ( $this->versionCompare(phpversion(),$version,'<') ){
			$this->render([
				'item'   => "PHP {$version}",
				'notice' => $this->translateString($this->strings['php']['required']) . '.'
			],$this->tpl);
		};
	}

	/**
	 * Check if plugin installed.
	 * 
	 * @access protected
	 * @param string $slug
	 * @return bool
	 */
	protected function isInstalled($slug)
	{
		if ( File::exists($this->getPluginDir("/{$slug}/{$slug}.php")) ) {
			return true;

		} elseif ( File::exists($this->getPluginDir("/{$slug}.php")) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check if plugin/module activated.
	 * 
	 * @access protected
	 * @param string $callable
	 * @return bool
	 */
	protected function isActivated($callable)
	{
		if ( $this->isPlugin("{$callable}/{$callable}.php") ) {
			return true;
			
		} elseif ( $this->isPluginClass($callable) || TypeCheck::isFunction($callable) ) {
			return true;

		} elseif ( defined($callable) ) {
			return true;

		} elseif ( extension_loaded($callable) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check server config.
	 * 
	 * @access protected
	 * @param string $name
	 * @param string $value
	 * @return bool
	 */
	protected function hasConfig($name, $value)
	{
		if ( (string)ini_get($name) == (string)$value ) {
			return true;
		}
		return false;
	}
}
