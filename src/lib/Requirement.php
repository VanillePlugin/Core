<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\int\RequirementInterface;

/**
 * Plugin requirements manager.
 */
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
	 * Init requirement.
	 */
	public function __construct()
	{
		$this->add([$this, 'requirePath']);
		$this->add([$this, 'requirePlugins']);
		$this->add([$this, 'requireOptions']);
		$this->add([$this, 'requireTemplates']);
		$this->add([$this, 'requireModules']);
		$this->add([$this, 'php']);

		// Set template
		$this->tpl = 'admin/inc/notice/requirement';
		$this->tpl = $this->applyPluginFilter('requirement-template', $this->tpl);

		// Set strings
		$this->strings = $this->applyPluginFilter('requirement-strings', [
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

		// Reset config
		$this->resetConfig();
	}
	
	/**
	 * @inheritdoc
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
			if ( !$this->isDir($path) || !$this->isWritable($path) ) {

		        // Try creating path
				if ( !$this->addDir($this->formatPath($path)) ) {

					$message = $this->transVar(
						$this->strings['path']['exists'],
						[
							$this->getPluginName(),
							$this->basename($path)
						]
					);

					$notice  = '<div class="';
					$notice .= $this->getNameSpace();
					$notice .= '-notice notice notice-error">';
					$notice .= '<p>';
					$notice .= '<i class="icon-close"></i> ';
					$notice .= '<strong>';
					$notice .= $this->trans('Warning') . ' : ';
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
	 * @inheritdoc
	 */
	public function requirePlugins()
	{
		// Check plugins
		if ( !($plugins = $this->getConfig()->requirements->plugins) ) {
			return;
		}

		// Requires plugins
		foreach ($this->uniqueMultiArray($plugins) as $plugin) {

			$name = $plugin->name ?? $plugin->slug;
			if ( !$this->isInstalled($plugin->slug) ) {
				
				$this->render($this->tpl, [
					'item'   => $name,
					'notice' => $this->trans($this->strings['plugin']['install'])
				]);

			} else {
				$callable = $plugin->callable ?? $plugin->slug;
				if ( !$this->isActivated($callable) ) {
					$this->render($this->tpl, [
						'item'   => $name,
						'notice' => $this->trans($this->strings['plugin']['activate'])
					]);
				}
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function requireOptions()
	{
		// Check options
		if ( !($options = $this->getConfig()->requirements->options) ) {
			return;
		}

		// Requires options
		foreach ($this->uniqueMultiArray($options) as $option) {

			$name = $option->name ?? $option->slug;
			if ( $this->getOption($option->slug) !== $option->value ) {
				$this->render($this->tpl, [
					'item'   => $name,
					'notice' => $this->trans($this->strings['option']['missing'])
				]);

			} elseif ( empty($this->getOption($option->slug)) ) {
				$this->render($this->tpl, [
					'item'   => $name,
					'notice' => $this->trans($this->strings['option']['empty'])
				]);
			}

		}
	}

	/**
	 * @inheritdoc
	 */
	public function requireTemplates()
	{
		// Check templates
		if ( !($templates = $this->getConfig()->requirements->templates) ) {
			return;
		}

		// Requires templates
		$slugs = [];
		$names = [];

		foreach ($this->uniqueMultiArray($templates) as $template) {
			$slugs[] = $template->slug;
			$names[] = $template->name ?? $template->slug;
		}

		if ( !$this->hasString($slugs, $this->getOption('template')) ) {

			if ( count($slugs) > 1 ) {
				// Check for multiple templates
				$item = implode(', ',$names);
				$notice = $this->trans($this->strings['template']['multiple']);

			} else {
				// Check for single template
				$item = trim(implode('',$names));
				$notice = $this->trans($this->strings['template']['single']);
			}

			$this->render($this->tpl, [
				'item'   => $item,
				'notice' => $notice,
			]);

		}
	}

	/**
	 * @inheritdoc
	 */
	public function requireModules()
	{
		// Check modules
		if ( !($modules = $this->getConfig()->requirements->modules) ) {
			return;
		}

		// Requires modules
		foreach ($this->uniqueMultiArray($modules) as $module) {

			if ( isset($module->override) ) {

				// Overrided module check
				$name = $module->override->name ?? '';
				$value = $module->override->value ?? '';

				if ( !$this->isActivated($module->callable) && !$this->isConfig($name,$value) ) {
					$notice = $this->transVar(
						$this->strings['module']['config'],
						[$name, $value]
					);
					$this->render($this->tpl, [
						'item'   => $module->name,
						'notice' => $notice
					]);
				}

			} else {
				
				// Single module check
				if ( !$this->isActivated($module->callable) ) {
					$this->render($this->tpl, [
						'item'   => $module->name,
						'notice' => $this->trans($this->strings['module']['required'])
					]);
				}
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function php()
	{
		// Check version
		if ( !($version = $this->getConfig()->requirements->php) ) {
			return;
		}

		if ( $this->isVersion(phpversion(), $version, '<') ) {
			$this->render($this->tpl, [
				'item'   => "PHP {$version}",
				'notice' => $this->trans($this->strings['php']['required'])
			]);
		};
	}

	/**
	 * Check whether plugin installed.
	 *
	 * @access protected
	 * @param string $slug
	 * @return bool
	 */
	protected function isInstalled(string $slug) : bool
	{
		if ( $this->isFile($this->getPluginDir("/{$slug}/{$slug}.php")) ) {
			return true;

		} elseif ( $this->isFile($this->getPluginDir("/{$slug}.php")) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check whether plugin or PHP module activated.
	 *
	 * @access protected
	 * @param string $callable
	 * @return bool
	 */
	protected function isActivated(string $callable) : bool
	{
		if ( $this->isPlugin("{$callable}/{$callable}.php") ) {
			return true;
			
		} elseif ( $this->isPluginClass($callable) ) {
			return true;

		} elseif ( $this->isType('function', $callable) ) {
			return true;

		} elseif ( defined($callable) ) {
			return true;

		} elseif ( $this->isModule($callable) ) {
			return true;
		}
		return false;
	}
}
