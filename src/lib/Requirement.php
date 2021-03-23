<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.5.1
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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

final class Requirement extends Notice implements RequirementInterface
{
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
					'notice' => $this->translateString('Required, Please install it')
				],'admin/notice/requirement');

			} else {

				if ( !$this->isActivated($plugin->callable) ) {
					$this->render([
						'item'   => $name,
						'notice' => $this->translateString('Required, Please activate it')
					],'admin/notice/requirement');
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
					'notice' => $this->translateString('Option Required')
				],'admin/notice/requirement');

			} else if ( empty($this->getOption($option->slug)) ) {
				$this->render([
					'item'   => $name,
					'notice' => $this->translateString('Option Not Defined')
				],'admin/notice/requirement');
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
				$notice = $this->translateString('One Of Templates Required');
			} else {
				$item = trim(implode('', $names));
				$notice = $this->translateString('Template Required');
			}
			$this->render([
				'item'   => $item,
				'notice' => $notice,
			],'admin/notice/requirement');
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
					'notice' => $this->translateString('Required, Please activate it')
				],'admin/notice/requirement');
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
				'notice' => $this->translateString('Required')
			],'admin/notice/requirement');
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
		}
	}

	/**
	 * @access protected
	 * @param string $callable
	 * @return bool
	 */
	protected function isActivated($callable)
	{
		if ( $this->isClass($callable) || $this->isFunction($callable) ) {
			return true;
		}
	}
}
