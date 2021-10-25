<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.3
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\int\RestApiInterface;
use \WP_REST_Server as WPRestServer;

abstract class AbstractRestAPI extends PluginOptions implements RestApiInterface
{
	/**
	 * @access protected
	 * @var object $auth Authentication
	 * @var string $version
	 * @var object $args
	 */
	protected $auth;
	protected $endpoint = 'default';
	protected $version = 'v1';
	protected $args = false;

	/**
	 * @access private
	 * @var boolean $isOverridable
	 */
	private $isOverridable = false;

	/**
	 * Register routes
	 *
	 * @access public
	 * @param WpRestServer $server
	 * @return void
	 */
	abstract public function registerRoutes(WpRestServer $server);

	/**
	 * Init api hook
	 *
	 * @access public
	 * @param string $method
	 * @return void
	 */
	public function init()
	{
		$this->addAction('rest_api_init',[$this,'registerRoutes']);
	}

	/**
	 * @access public
	 * @param bool $override
	 * @return void
	 */
	public function setOverride($override = false)
	{
		$this->override = $override;
	}

	/**
	 * @access public
	 * @param string $endpoint
	 * @return void
	 */
	public function setEndpoint($endpoint = 'default')
	{
		$this->endpoint = $endpoint;
	}

	/**
	 * @access public
	 * @param string $version
	 * @return void
	 */
	public function setVersion($version = 'v1')
	{
		$this->version = $version;
	}

	/**
	 * @access public
	 * @param object $args
	 * @return void
	 */
	public function addParameters($args = false)
	{
		$this->args = TypeCheck::isArray($args)
		? Stringify::toObject($args) : $args;
	}

	/**
	 * @access public
	 * @param object $plugin
	 * @return void
	 */
	public function setAuthentication($plugin)
	{
		$this->auth = new Authentication($plugin);
	}

	/**
	 * @access protected
	 * @param string $route
	 * @param string $methods
	 * @param string $callback
	 * @param string $permission
	 * @return void
	 */
	protected function register($route, $methods = 'GET', $callback = 'defaultCallback', $permission = 'isPermitted')
	{
		// Override default routes using custom plugin settings
		if ( $this->args ) {
			$route = !empty($this->args->{$callback}['route'])
			? $this->args->{$callback}['route'] : $route;
		}

		// Register rest route
	    register_rest_route("{$this->endpoint}/{$this->version}", $route, [
	        'methods'  => $methods,
	        'callback' => [$this, $callback],
	        'permission_callback' => [$this, $permission]
	    ], $this->isOverridable);
	}

	/**
	 * @access protected
	 * @param void
	 * @return true
	 */
	protected function defaultCallback()
	{
		return true;
	}

	/**
	 * @access protected
	 * @param array $args
	 * @return bool
	 */
	protected function isPermitted($args = [])
	{
		if ( $this->auth && $this->auth->isAllowed($args) ) {
         	return true;
		}
		return false;
	}
}
