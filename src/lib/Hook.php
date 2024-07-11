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
 * Plugin hooks manager.
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
	public const GROUP  = 'options';

	/**
	 * @access private
	 * @var string $filter
	 * @var string $option
	 * @var string $group
	 */
	private $filter;
	private $option;
	private $group;

	/**
	 * Init hook.
	 */
	public function __construct(string $filter = self::FILTER, string $option = self::OPTION)
	{
		$this->filter = $filter;
		$this->option = $option;
		$this->group  = self::GROUP;
	}

	/**
	 * Get valid hooks.
	 * [Filter: {plugin}-{filter}].
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
	 * @return void
	 */
	public function register()
	{
		$this->registerPluginOption($this->group, $this->option);
	}

	/**
	 * Add default hooks if not exists.
	 *
	 * @access public
	 * @param array $hooks
	 * @return bool
	 */
	public function add(array $hooks) : bool
	{
		return $this->addPluginOption($this->option, $hooks);
	}

	/**
	 * Set hooks group.
	 *
	 * @access public
	 * @param string $group
	 * @return void
	 */
	public function setGroup(string $group = self::GROUP)
	{
		$this->group = $group;
	}
}
