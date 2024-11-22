<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
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
	 * @var string FILTER, Hooks filter name
	 * @var string OPTION, Hooks option name
	 * @var string GROUP, Hooks option group
	 * @var string MAX, Hooks max inputs
	 * @var array TAGS, Hooks allowed tags
	 * @var array INPUT, Hooks default inputs args
	 */
	public const FILTER = 'admin-hooks';
	public const OPTION = 'hooks';
	public const GROUP  = 'options';
	public const MAX    = 10;
	public const TAGS   = ['input', 'checkbox', 'color', 'button', 'p'];
	public const INPUT  = [
		'type'        => 'text',
		'tag'         => 'input',
		'column'      => 3,
		'default'     => null,
		'disabled'    => false,
		'required'    => false,
		'name'        => false,
		'title'       => false,
		'description' => false,
		'placeholder' => false,
		'class'       => false,
		'max'         => false,
		'min'         => false,
		'step'        => false
	];

	/**
	 * @access private
	 * @var string $filter
	 * @var string $option
	 * @var string $group
	 * @var array $types
	 * @var array $input
	 * @var string $max
	 */
	private $filter;
	private $option;
	private $group;
	private $tags;
	private $input;
	private $max;

	/**
	 * Init hook.
	 */
	public function __construct(string $filter = self::FILTER, string $option = self::OPTION)
	{
		$this->filter = $filter;
		$this->option = $option;
		$this->group  = $this->applyPluginFilter('hooks-group', self::GROUP);
		$this->tags   = $this->applyPluginFilter('hooks-tags', self::TAGS);
		$this->input  = $this->applyPluginFilter('hooks-input', self::INPUT);
		$this->max    = $this->applyPluginFilter('hooks-max', self::MAX);
	}

	/**
	 * Get filtered hooks.
	 * [Filter: {plugin}-{filter}].
	 *
	 * @access public
	 * @return mixed
	 */
	public function get()
	{
		if ( $this->hasPluginFilter($this->filter) ) {
			$registered = $this->getRegistered();
			$hooks = $this->applyPluginFilter($this->filter, $registered);
			return $this->filter($registered, $hooks);
		}

		return false;
	}

	/**
	 * Get hooks values.
	 *
	 * @access public
	 * @param string $group
	 * @param string $option
	 * @return mixed
	 */
	public function getValues(?string $group = null, ?string $option = null)
	{
		$values = $this->getRegistered();

		foreach ($values as $key => $value) {
			$values[$key] = $this->map(function($item) {
				return $item['value'] ?? null;
			}, $value);
		}

		if ( $group && isset($values[$group]) ) {
			$values = (array)$values[$group];
			if ( $option ) {
				return $values[$option] ?? null;
			}
		}

		return $values;
	}

	/**
	 * Update hooks values.
	 *
	 * @access public
	 * @param array $data
	 * @return bool
	 */
	public function updateValues(array $data) : bool
	{
		$registered = $this->getRegistered();
		foreach ($data as $group => $inputs) {
			foreach ($inputs as $key => $value) {
				$registered[$group][$key]['value'] = $value;
			}
		}
		return $this->update($registered);
	}

	/**
	 * Add hooks.
	 *
	 * @access public
	 * @return bool
	 */
	public function add() : bool
	{
		$hooks = [];
		foreach ($this->getHooks() as $hook) {
			$hooks[$hook] = [];
		}
		return $this->addPluginOption($this->option, $hooks);
	}

	/**
	 * Register hooks inside option group.
	 *
	 * @access public
	 * @return void
	 */
	public function register()
	{
		$this->registerPluginOption($this->group, $this->option);
	}

	/**
	 * Filter hooks.
	 *
	 * @access private
	 * @param array $registered
	 * @param array $hooks
	 * @return array
	 */
	private function filter(array $registered, array $hooks) : array
	{
		foreach ($hooks as $group => $inputs) {

			// Validate group
			if ( !$this->inArray($group, $this->getHooks()) ) {
				unset($hooks[$group]);
				continue;
			}

			// Validate group items
			if ( count($registered[$group]) == $this->max ) {
				continue;
			}

			// Validate inputs
			foreach ($inputs as $key => $args) {

				if ( !$this->isType('string', $key) ) {
					continue;
				}
				if ( !$this->isType('array', $args) ) {
					continue;
				}

				// Format hooked input
				$hooked = $hooks[$group][$key];
				$hooked = $this->mergeArray($this->input, $hooked);
				unset($hooked['value']);

				// Validate tags
				if ( !$this->inArray($hooked['tag'], $this->tags)) {
					continue;
				}

				// Add or update input
				if ( !isset($registered[$group][$key]) ) {
					$registered[$group][$key] = $hooked;
					$this->update($registered);

				} else {
					$saved = $registered[$group][$key];
					$temp  = $saved['value'] ?? null;
					unset($saved['value']);

					if ( $this->diffArray($saved, $hooked) ) {
						
						$registered[$group][$key] = $hooked;
						$this->update($registered);

						if ( $this->isType('null', $temp) ) {
							$registered[$group][$key]['value'] = $temp;
						}
					}
				}

			}

		}

		return $registered;
	}

	/**
	 * Get registered hooks.
	 *
	 * @access private
	 * @return array
	 */
	private function getRegistered() : array
	{
		return (array)$this->getPluginOption($this->option, []);
	}

	/**
	 * Update hooks.
	 *
	 * @access private
	 * @param array $data
	 * @return bool
	 */
	private function update(array $data) : bool
	{
		return $this->updatePluginOption($this->option, $data);
	}
}
