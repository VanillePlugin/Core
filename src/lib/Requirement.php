<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.4
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\RequirementInterface;
use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\File;

class Requirement extends Notice implements RequirementInterface
{
	/**
	 * @param PluginNameSpaceInterface $plugin
	 * @return void
	 */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);
		$this->init([$this,'requirePlugins']);
		$this->init([$this,'requireOptions']);
		$this->init([$this,'requireTemplate']);
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

			if ( !$this->isInstalled($plugin->slug) ) {
				$this->render([
					'item'   => $plugin->name,
					'notice' => $this->translateString('Required, Please install it.')
				],'admin/notice/requirement');

			} else {

				if ( !$this->isActivated($plugin->callable) ) {
					$this->render([
						'item'   => $plugin->name,
						'notice' => $this->translateString('Required, Please activate it.')
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
			if ( $this->getOption($option->slug) !== $option->value ) {
				$this->render([
					'item'   => $option->name,
					'notice' => $this->translateString('Option Required.')
				],'admin/notice/requirement');

			} else if ( empty($this->getOption($option->slug)) ) {
				$this->render([
					'item'   => $option->name,
					'notice' => $this->translateString('Option Not Defined.')
				],'admin/notice/requirement');
			}
		}
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function requireTemplate()
	{
		// Check template
		if ( !($template = $this->getConfig()->requirement->template) ) {
			return;
		}

		// Requires template
		if ( $this->getOption('template') !== $template->slug ) {
			$this->render([
				'item'   => $template->name,
				'notice' => $this->translateString('Template Required.')
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
					'notice' => $this->translateString('Required, Please activate it.')
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
		if ( version_compare(phpversion(),$version,'<') ){
			$this->render([
				'item'   => 'PHP',
				'notice' => $this->translateString('Update Required')
			],'admin/notice/requirement');
		};
	}

	/**
	 * @access protected
	 * @param string $slug
	 * @return boolean
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
	 * @return boolean
	 */
	protected function isActivated($callable)
	{
		if ( $this->isClass($callable) || $this->isFunction($callable) ) {
			return true;
		}
	}
}
