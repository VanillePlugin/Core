<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.8
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\RequirementInterface;
use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\TypeCheck;

final class Requirement extends Notice implements RequirementInterface
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
		$this->init([$this,'requirePlugins']);
		$this->init([$this,'requireOptions']);
		$this->init([$this,'requireTemplates']);
		$this->init([$this,'requireModules']);
		$this->init([$this,'php']);

		// Set template
		$this->tpl = $this->applyPluginFilter('requirement-template','admin/inc/notice/requirement');
		
		// Set strings
		$this->strings = $this->applyPluginFilter('requirement-strings',[
			'plugin' => [
				'install'  => 'Required, Please install it',
				'activate' => 'Required, Please activate it'
			],
			'option' => [
				'missing' => 'Option Required',
				'empty'   => 'Option Not Defined'
			],
			'template' => [
				'single'   => 'Template Required',
				'multiple' => 'One Of Templates Required'
			],
			'module' => [
				'required' => 'Required on server, Please activate it'
			],
			'php' => [
				'required' => 'Required'
			]
		]);
	}

	/**
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
		foreach ($plugins as $plugin) {

			$name = isset($plugin->name) ? $plugin->name : $plugin->slug;
			if ( !$this->isInstalled($plugin->slug) ) {
				
				$this->render([
					'item'   => $name,
					'notice' => $this->translateString($this->strings['plugin']['install'])
				],$this->tpl);

			} else {

				$callable = isset($plugin->callable) ? $plugin->callable : $plugin->slug;
				if ( !$this->isActivated($callable) ) {
					$this->render([
						'item'   => $name,
						'notice' => $this->translateString($this->strings['plugin']['activate'])
					],$this->tpl);
				}
			}
		}
	}

	/**
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
		foreach ($options as $option) {
			$name = isset($option->name) ? $option->name : $option->slug;
			if ( $this->getOption($option->slug) !== $option->value ) {
				$this->render([
					'item'   => $name,
					'notice' => $this->translateString($this->strings['option']['missing'])
				],$this->tpl);

			} else if ( empty($this->getOption($option->slug)) ) {
				$this->render([
					'item'   => $name,
					'notice' => $this->translateString($this->strings['option']['empty'])
				],$this->tpl);
			}
		}
	}

	/**
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
		foreach ($templates as $template) {
			$slugs[] = $template->slug;
			$names[] = isset($template->name) ? $template->name : $template->slug;
		}

		if ( !Stringify::contains($slugs, $this->getOption('template')) ) {
			if ( count($slugs) > 1 ) {
				$item = implode(', ', $names);
				$notice = $this->translateString($this->strings['template']['multiple']);
			} else {
				$item = trim(implode('', $names));
				$notice = $this->translateString($this->strings['template']['single']);
			}
			$this->render([
				'item'   => $item,
				'notice' => $notice,
			],$this->tpl);
		}
	}

	/**
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
		foreach ($modules as $module) {
			if ( !$this->isActivated($module->callable) ) {
				$this->render([
					'item'   => $module->name,
					'notice' => $this->translateString($this->strings['module']['required'])
				],$this->tpl);
			}
		}
	}

	/**
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
				'notice' => $this->translateString($this->strings['php']['required'])
			],$this->tpl);
		};
	}

	/**
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
		}
		return false;
	}
}
