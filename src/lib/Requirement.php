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

/**
 * Plugin requirements manager.
 */
class Requirement extends Notice
{
	/**
	 * @access protected
	 * @var array $dependency
	 * @var array $messages
	 * @var string $template
	 */
	protected $dependency;
	protected $messages;
	protected $template;

	/**
	 * Init requirements.
	 * [Action: admin-init].
	 */
	public function __construct()
	{
		$data = $this->getRequirements();
		$this->dependency = $data['dependency'];
		$this->messages = $data['messages'];
		$this->template = $data['template'];
		$this->verify();
	}

	/**
	 * Verify requirements.
	 *
	 * @access protected
	 * @return void
	 */
	protected function verify()
	{
		$this->requirePaths();
		$this->requirePlugins();
		$this->requireThemes();
		$this->requireOptions();
		$this->requireModules();
		$this->requirePhp();
	}

	/**
	 * Verify required paths.
	 *
	 * @access protected
	 * @return void
	 */
	protected function requirePaths()
	{
		if ( !($paths = $this->dependency['paths']) ) {
			return;
		}

		$paths = $this->uniqueMultiArray($paths);
		foreach ($paths as $path) {
			
			$path = $this->getRoot($path);

			if ( !$this->isDir($path) ) {
				$message = $this->messages['paths']['missing'];
				$message = $this->transVar($message, [$path]);
				$this->display(function() use ($message) {
					$this->do($message, 'error');
				});
				continue;
			}

			if ( !$this->isReadable($path) ) {
				$message = $this->messages['paths']['readable'];
				$message = $this->transVar($message, [$path]);
				$this->display(function() use ($message) {
					$this->do($message, 'error');
				});
				continue;
			}

			if ( !$this->isWritable($path) ) {
				$message = $this->messages['paths']['writable'];
				$message = $this->transVar($message, [$path]);
				$this->display(function() use ($message) {
					$this->do($message, 'error');
				});
				continue;
			}

		}
	}
	
	/**
	 * Verify required plugins.
	 *
	 * @access protected
	 * @return void
	 */
	protected function requirePlugins()
	{
		if ( !($plugins = $this->dependency['plugins']) ) {
			return;
		}

		$plugins = $this->uniqueMultiArray($plugins);
		foreach ($plugins as $plugin) {

			$slug = $plugin['slug'] ?? false;
			if ( !$slug ) {
				$slug = $this->slugify($plugin['name']);
			}

			if ( !$this->isInstalled($slug) ) {
				$message = $this->messages['plugins']['missing'];
				$message = $this->transVar($message, [$plugin['name']]);
				$this->display(function() use ($message) {
					$this->do($message, 'error');
				});
				continue;
			}

			$callable = $plugin['callable'] ?? false;
			if ( !$callable ) {
				$callable = $this->undash(
					$this->slugify($plugin['name'])
				);
			}

			if ( !$this->isActivated($callable) ) {
				$message = $this->messages['plugins']['missing'];
				$message = $this->transVar($message, [$plugin['name']]);
				$this->display(function() use ($message) {
					$this->do($message, 'error');
				});
				continue;
			}

		}
	}

	/**
	 * Verify required themes.
	 *
	 * @access protected
	 * @return void
	 */
	protected function requireThemes()
	{
		if ( !($themes = $this->dependency['themes']) ) {
			return;
		}

		$themes = $this->uniqueMultiArray($themes);
		foreach ($themes as $key => $theme) {
			$themes[$theme['slug']] = $theme['name'];
			unset($themes[$key]);
		}

		$active = $this->getOption('template');
		if ( !$this->inArray($active, $this->arrayKeys($themes)) ) {

			$count   = (count($themes) > 1 ) ? 'multiple' : 'single';
			$themes  = $this->arrayValues($themes);
			$themes  = implode(', ', $themes);
			
			$message = $this->messages['themes'][$count];
			$message = $this->transVar($message, [$themes]);
			
			$this->display(function() use ($message) {
				$this->do($message, 'error');
			});

		}
	}

	/**
	 * Verify required options.
	 *
	 * @access protected
	 * @return void
	 */
	protected function requireOptions()
	{
		if ( !($options = $this->dependency['options']) ) {
			return;
		}

		$options = $this->uniqueMultiArray($options);
		foreach ($options as $option) {

			$slug = $option['slug'] ?? false;
			if ( !$slug ) {
				$slug = $this->undash(
					$this->slugify($option['name'])
				);
			}

			if ( !$this->isOption($slug) ) {
				$message = $this->messages['options']['missing'];
				$message = $this->transVar($message, [$option['name']]);
				$this->display(function() use ($message) {
					$this->do($message, 'error');
				});
				continue;
			}

			if ( $this->getOption($slug) !== $option['value'] ) {
				$message = $this->messages['options']['invalid'];
				$message = $this->transVar($message, [
					$option['name'], $option['value']
				]);
				$this->display(function() use ($message) {
					$this->do($message, 'error');
				});
				continue;
			}

		}
	}

	/**
	 * Verify required modules.
	 *
	 * @access protected
	 * @return void
	 */
	protected function requireModules()
	{
		if ( !($modules = $this->dependency['modules']) ) {
			return;
		}

		$modules = $this->uniqueMultiArray($modules);
		foreach ($modules as $module) {

			$slug = $module['slug'] ?? false;
			if ( !$slug ) {
				$slug = $this->undash(
					$this->slugify($module['name'])
				);
			}

			if ( !$this->isModule($slug) && !$this->isServerModule($slug) ) {

				if ( isset($module['override']) ) {

					$option = $module['override'];
					$oSlug  = $option['slug'] ?? false;
					if ( !$oSlug ) {
						$oSlug = $this->undash(
							$this->slugify($option['name'])
						);
					}

					if ( $this->getOption($oSlug) !== $option['value'] ) {
						$message = $this->messages['modules']['override'];
						$message = $this->transVar($message, [
							$module['name'], $option['name'], $option['value']
						]);
						$this->display(function() use ($message) {
							$this->do($message, 'error');
						});
						continue;
					}

				}

				$message = $this->messages['modules']['missing'];
				$message = $this->transVar($message, [$module['name']]);
				$this->display(function() use ($message) {
					$this->do($message, 'error');
				});
				continue;

			}

		}
	}

	/**
	 * Verify required PHP version.
	 *
	 * @access protected
	 * @return void
	 */
	protected function requirePhp()
	{
		if ( !($php = $this->dependency['php']) ) {
			return;
		}

		if ( $this->isVersion(phpversion(), $php, '<') ) {
			$message = $this->messages['php']['version'];
			$message = $this->transVar($message, $php);
			$this->display(function() use ($message) {
				$this->do($message, 'error');
			});
		}
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
		$file = "{$slug}.php";
		if ( !$this->hasString($slug, '/') ) {
			$file = "{$slug}/{$file}";
		}

		if ( $this->isFile($this->getPluginDir($file)) ) {
			return true;

		} elseif ( $this->isFile($this->getPluginDir("{$slug}.php")) ) {
			return true;

		} elseif ( $this->isFile($this->getPluginMuDir("{$slug}.php")) ) {
			return true;
		}
		
		return false;
	}

	/**
	 * Check whether plugin activated.
	 *
	 * @access protected
	 * @param string $callable
	 * @return bool
	 */
	protected function isActivated(string $callable) : bool
	{
		$file = "{$callable}.php";
		if ( !$this->hasString($callable, '/') ) {
			$file = "{$callable}/{$file}";
		}

		if ( $this->isPlugin($file) ) {
			return true;
			
		} elseif ( $this->isPluginClass($callable) ) {
			return true;

		} elseif ( $this->isType('function', $callable) ) {
			return true;

		} elseif ( defined($callable) ) {
			return true;
		}

		return false;
	}
}
