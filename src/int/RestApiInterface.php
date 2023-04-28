<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface RestApiInterface
{
	/**
	 * @param PluginNameSpaceInterface $plugin
	 */
	function __construct(PluginNameSpaceInterface $plugin);

	/**
	 * Init api hook
	 *
	 * @param string $method
	 * @return void
	 */
	function init();

	/**
	 * @param bool $override
	 * @return void
	 */
	function setOverride($override = false);

	/**
	 * @param string $endpoint
	 * @return void
	 */
	function setEndpoint($endpoint = 'default');

	/**
	 * @param string $version
	 * @return void
	 */
	function setVersion($version = 'v1');

	/**
	 * @param object $args
	 * @return void
	 */
	function addParameters($args = false);

	/**
	 * @param object $plugin
	 * @return void
	 */
	function setAuthentication($plugin);
}
