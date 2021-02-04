<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.7
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\int;

use VanillePlugin\int\PluginNameSpaceInterface;
use \WP_REST_Server as WpRestServer;

interface RestApiInterface 
{
	/**
	 * @param PluginNameSpaceInterface $plugin
	 * @return void
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
	 * @param boolean $override
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
