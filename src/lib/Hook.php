<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

/**
 * Plugin custom admin hooks.
 * @internal
 */
final class Hook
{
	use \VanillePlugin\VanillePluginOption;

	/**
	 * @access public
	 * @var string FILTER
	 * @var string OPTION
	 */
	public const FILTER = 'admin-extras';
	public const OPTION = 'extras';

	/**
	 * @access private
	 * @var string $filter
	 * @var string $option
	 */
	private $filter;
	private $option;

	/**
	 * Init hook.
	 */
	public function __construct(string $filter = self::FILTER, string $option = self::OPTION)
	{
		// Init config
		$this->initConfig();

		$this->filter = $filter;
		$this->option = $option;
	}

	/**
	 * Get valid hooks.
	 *
	 * @access public
	 * @return mixed
	 */
	public function get()
	{
		// Filter settings
		if ( $this->hasFilter($this->filter) ) {

			$default = $this->getPluginOption($this->option, 'array', []);
			$extras  = $this->applyPluginFilter($this->filter, $default);

			// Reset settings
			foreach ($extras as $type => $extra) {
				foreach ($extra as $key => $value) {
					if ( !isset($value['enable']) || $value['enable'] !== true ) {
						// Hook removed or not enabled
						unset($extras[$type][$key]);
						unset($default[$type][$key]);

					} else {
 						if ( isset($default[$type][$key]['value']) ) {
							// Hook registred
							$extras[$type][$key]['value'] = $default[$type][$key]['value'];
						}
					}
				}
			}

			// Return filtered extras
			$this->updateOption($this->option, $default);
			return $extras;
		}

		return false;
	}

	/**
	 * Register hooks inside group.
	 *
	 * @access public
	 * @param string $group
	 * @return void
	 */
	public function register(string $group)
	{
		$this->registerPluginOption($group, $this->option);
	}

	/**
	 * Add default hooks if not exists.
	 *
	 * @access public
	 * @param array $default
	 * @return bool
	 */
	public function add(array $default) : bool
	{
		return $this->addPluginOption($this->option, $default);
	}
}
