<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\int\RestApiInterface;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;

/**
 * Wrapper class for internal REST API.
 * @uses JWT is recommended for external use.
 */
abstract class RestAPI implements RestApiInterface
{
    use \VanillePlugin\VanillePluginConfig,
		\VanillePlugin\tr\TraitRequestable,
		\VanillePlugin\tr\TraitAuthenticatable,
		\VanillePlugin\tr\TraitHookable;

	/**
	 * @access private
	 * @var string ENDPOINT Default REST endpoint
	 * @var string VERSION Default REST version
	 * @var string KEY Default REST public key
	 * @var mixed METHOD Default REST method
	 */
	private const ENDPOINT = 'api';
	private const VERSION  = 'v1';
	private const KEY      = 'public-key';
	private const METHOD   = 'GET';

	/**
	 * @access protected
	 * @var string $endpoint
	 * @var string $version
	 * @var array $tokens
	 * @var array $routes
	 * @var bool $override
	 */
	protected $endpoint;
	protected $version;
	protected $auth;
	protected $tokens = [];
	protected $routes = [];
	protected $override = false;

	/**
	 * Init endpoint action.
	 *
	 * @access protected
	 * @return mixed
	 */
	abstract protected function initAction();

	/**
	 * Init endpoint permission.
	 * 
	 * @access protected
	 * @param array $args
	 * @return bool
	 */
	abstract protected function initPermission(array $args = []) : bool;

	/**
	 * @inheritdoc
	 */
	public function addRoutes(WP_REST_Server $server)
	{
		foreach ($this->getRoutes() as $callback => $args) {
			$this->register($args->route, $args->methods, $callback, "{$callback}Permission");
		}
	}

	/**
	 * @inheritdoc
	 */
	public function initTokens()
	{
		$key = $this->applyNamespace('tokens');
		if ( !($this->tokens = $this->getOption("--{$key}", false)) ) {
			$this->updateOption("--{$key}", []);
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function addPublicKey(int $user, string $public)
	{
		$key = $this->applyNamespace(self::KEY);
		$this->addUserMeta("--{$key}", $public, $user);
	}

	/**
	 * @inheritdoc
	 */
	public function updatePublicKey(int $user, string $public)
	{
		$key = $this->applyNamespace(self::KEY);
		$this->updateUserMeta("--{$key}", $public, $user);
	}

	/**
	 * @inheritdoc
	 */
	public function deletePublicKey(int $user)
	{
		$key = $this->applyNamespace(self::KEY);
		$this->deleteUserMeta("--{$key}", $user);
	}

	/**
	 * @inheritdoc
	 */
	public static function registerRoute(string $endpoint, string $route, array $args, bool $override) : bool
	{
	    return register_rest_route($endpoint, $route, $args, $override);
	}

	/**
	 * Init REST,
	 * [Action: rest-api].
	 * 
	 * @access protected
	 * @param string $version
	 * @param string $endpoint
	 * @return void
	 */
	protected function initRest(?string $version = self::VERSION, ?string $endpoint = self::ENDPOINT)
	{
		$this->version = $version;
		$this->endpoint = $endpoint;
		$this->routes = $this->getRoutes();
		$this->addAction('rest-api', [$this, 'addRoutes']);
	}

	/**
	 * Register plugin route.
	 * 
	 * @access protected
	 * @param string $route
	 * @param mixed $method
	 * @param string $a, Endpoint action
	 * @param string $p, Endpoint permission
	 * @return void
	 */
	protected function register(string $route, $method = self::METHOD, $a = 'initAction', $p = 'initPermission')
	{
		$endpoint = $this->formatPath(
			"{$this->endpoint}/{$this->version}"
		);
	    self::registerRoute($endpoint, $route, [
	        'methods'             => $method,
	        'callback'            => [$this, $a],
	        'permission_callback' => [$this, $p]
	    ], $this->override);
	}

	/**
	 * Check whether user is authenticated.
	 * 
	 * @access protected
	 * @param array $args
	 * @return bool
	 */
	protected function isAuthenticated(array $args = []) : bool
	{
		// Get public key
		if ( !($key = $this->getBearerToken()) ) {
			return false;
		}

		// Get user id by public key
		if ( ($id = $this->getUserId($key)) ) {

			// Validate public key
			if ( isset($this->tokens[$id]) ) {

				// Authenticate with public & secret
				$public = $this->tokens[$id]['public'];
				$secret = $this->tokens[$id]['secret'];
				$prefix = $args['prefix'] ?? '';

				$authenticated = false;
				if ( ($match = $this->matchToken($public, $secret, $prefix)) ) {
					$authenticated = $this->authenticate($match['username'], $match['password']);
				}

				// Check authenticated user
				if ( $authenticated ) {
					// check role
					$role = $args['role'] ?? 'administrator';
					if ( $this->hasString($authenticated->caps,$role) ) {
						return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * Get user Id using public key.
	 * 
	 * @access private
	 * @param string $public
	 * @return mixed
	 */
	private function getUserId(string $public)
	{
		$key   = $this->applyNamespace(self::KEY);
		$users = $this->getUserByMeta($key, $public);
		$user  = $this->shiftArray($users);
		return $user['id'] ?? false;
	}
}
