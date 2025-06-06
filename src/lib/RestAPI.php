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

use VanillePlugin\inc\Restful;
use VanillePlugin\int\RestfulInterface;

/**
 * Plugin REST API controller.
 * JWT is recommended for external use.
 *
 * - Token auth
 * - Basic auth
 * - Basic application auth
 */
class RestAPI implements RestfulInterface
{
	use \VanillePlugin\VanillePluginOption,
		\VanillePlugin\tr\TraitThrowable;

	/**
	 * @access protected
	 * @var string AUTH REST auth method
	 * @var string VERSION Default REST version
	 * @var array SETTINGS Default REST settings
	 */
	protected const AUTH     = 'basic';
	protected const VERSION  = 'v1';
	protected const SETTINGS = [
		'show-in-index' => false
	];

	/**
	 * @access protected
	 * @var string $namespace
	 * @var string $version
	 * @var string $prefix
	 */
	protected $namespace;
	protected $version;
	protected $prefix;
	protected $auth;

	/**
	 * @inheritdoc
	 */
	public function __construct(?string $namespace = null, ?string $version = null)
	{
		$this->namespace = $namespace ?: $this->getNameSpace();
		$this->version   = $version   ?: static::VERSION;
		$this->auth      = static::AUTH;
	}

	/**
	 * @inheritdoc
	 */
	public final function register() : self
	{
		$this->namespace = $this->formatPath(
			"{$this->namespace}/{$this->version}"
		);
		$this->addAction('rest-api-init', [$this, 'addRoutes']);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addRoutes($server)
	{
		foreach ($this->getRoutes() as $item) {
			$this->add($item);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function prefix(string $prefix) : self
	{
		$this->addFilter('rest-api-prefix', function() use ($prefix) {
			return $prefix;
		}, 99);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function noIndex(bool $grant = true) : self
	{
		$noIndex = function($response) use ($grant) {

			if ( !$grant ) {
				return $this->doError(403, 'REST API index disabled');
			}

			if ( !$this->isAuthorized() ) {
				return $this->doError(401, 'REST API index restricted');
			}

			return $response;

		};

		$this->addFilter('rest-api-index', $noIndex, 99);
		$this->addFilter('rest-namespace-index', $noIndex, 99);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function noRoute(array $except = []) : self
	{
		$this->addFilter('rest-api-endpoint', function($rest) use ($except) {

			if ( $except ) {
				foreach ($rest as $route => $value) {
					if ( !$this->inArray($route, $except) ) {
						unset($rest[$route]);
					}
				}
				return $rest;
			}

			unset($rest);
			return [];

		}, 0);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function noPadding() : self
	{
		$this->addFilter('rest-api-jsonp', function() {
			return false;
		}, 99);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function override() : self
	{
		$this->addFilter('rest-api-response', function($response) {

			if ( isset($response['code']) ) {

				if ( $response['code'] == $this->undash('rest-no-route') ) {
					$this->setResponse('REST API route not found', [], 'error', 404);
				}
				if ( $response['code'] == $this->undash('rest-forbidden') ) {
					$this->setResponse('REST API route forbidden', [], 'error', 403);
				}
				if ( $response['code'] == $this->undash('rest-cookie-invalid-nonce') ) {
					$this->setResponse('REST API route invalid cookie', [], 'error', 403);
				}
				if ( $response['code'] == $this->undash('rest-invalid-type') ) {
					$this->setResponse('REST API route invalid type', [], 'error', 422);
				}
				if ( $response['code'] == $this->undash('rest-invalid-pattern') ) {
					$this->setResponse('REST API route invalid pattern', [], 'error', 422);
				}
				if ( $response['code'] == $this->undash('rest-invalid-json') ) {
					$this->setResponse('REST API route invalid json', [], 'error', 422);
				}

			}
			
			return $response;

		}, 99);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function disable()
	{
		$this->addFilter('rest-api-error', function() {
			return $this->doError(403, 'REST API disabled');
		}, 99);
	}

	/**
	 * @inheritdoc
	 */
	public function restrict(array $rules)
	{
		$this->addFilter('rest-api-error', function($response) use ($rules) {

			if ( $this->isError($response) ) {
				return $response;
			}

			if ( $this->restrictByRules($rules) ) {
				return $this->doError();
			}

			return $response;

		}, 99);
	}

	/**
	 * @inheritdoc
	 */
	public function action($request)
	{
		return $this->doResponse('default');
	}

	/**
	 * @inheritdoc
	 */
	public function access($request)
	{
		if ( !$this->isAuthorized() ) {
			return $this->doError(401);
		}
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function internal($request)
    {
        if ( $this->isLoggedIn() ) {
            return $this->hasCap('manage-options');
        }
        return false;
    }

	/**
	 * @inheritdoc
	 */
	public function setAuthMethod(string $auth)
    {
        $this->auth = $auth;
    }

	/**
     * Fetch response body.
     *
	 * @access public
	 * @param string $method
	 * @param string $route
	 * @param array $atts
	 * @return string
	 */
	public function fetch(string $method, string $route, array $atts = []) : string
	{
	    return Restful::fetch($method, $route, $atts);
	}

	/**
     * Register route.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function registerRoute(string $namespace, string $route, array $args, bool $override = false) : bool
	{
		$args['action'] = [$this, $args['action']];
		$args['access'] = [$this, $args['access']];
	    return Restful::register($namespace, $route, $args, $override);
	}

	/**
     * Send restful response.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function doResponse($data = [], int $code = 200, array $headers = []) : object
	{
	    return Restful::response($data, $code, $headers);
	}

	/**
     * Send error.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function doError(int $code = 403, ?string $message = null, $data = []) : object
	{
	    return Restful::error($code, $message, $data);
	}

	/**
     * Send request.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function doRequest(string $method, string $route, array $atts = []) : object
	{
	    return Restful::request($method, $route, $atts);
	}

	/**
     * Get request route.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getRoute($request)
	{
		if ( ($route = Restful::getRoute($request)) ) {
			return $this->removeString($this->namespace, $route);
		}
	    return false;
	}

	/**
     * Get request attributes.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getAttributes($request) : array
	{
	    return Restful::getAttributes($request);
	}

	/**
     * Get request params (POST).
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected static function getParams($request) : array
	{
		return Restful::getParams($request);
	}

	/**
     * Get request body content.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getBody($request) : string
	{
		return Restful::getBody($request);
	}

	/**
     * Get request body parameters (POST).
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getBodyParams($request) : array
	{
		return Restful::getBodyParams($request);
	}

	/**
     * Get request query parameters (GET).
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getQueryParams($request) : array
	{
		return Restful::getQueryParams($request);
	}

	/**
     * Get request file parameters (FILES).
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getFileParams($request) : array
	{
		return Restful::getFileParams($request);
	}

	/**
     * Get request url parameters (URL).
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getUrlParams($request) : array
	{
		return Restful::getUrlParams($request);
	}

	/**
     * Get request headers.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getHeaders($request) : array
	{
		return Restful::getHeaders($request);
	}

	/**
     * Get request header value.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getHeader($request, string $key)
	{
		return Restful::getHeader($request, $key);
	}

	/**
     * Get request method.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getMethod($request) : string
	{
		return Restful::getMethod($request);
	}

	/**
     * Check request parameter.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasParam($request, string $key) : bool
	{
		return Restful::hasParam($request, $key);
	}

	/**
     * Check valid request parameter.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isValidParam($request, string $key) : bool
	{
		return Restful::isValidParam($request, $key);
	}

	/**
     * Check POST method.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isPost($request) : bool
	{
		return ($this->getMethod($request) == 'POST');
	}

	/**
     * Check GET method.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isGet($request) : bool
	{
		return ($this->getMethod($request) == 'GET');
	}

	/**
     * Check DELETE method.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isDelete($request) : bool
	{
		if ( $this->getMethod($request) == 'DELETE' ) {
			return true;
		}
		if ( $this->isPost($request) ) {
			return ($this->getHeader($request, 'X-Method') == 'DELETE');
		}
		return false;
	}

	/**
	 * Add route.
	 *
	 * @access protected
	 * @param array $item
	 * @return void
	 */
	protected function add(array $item)
	{
		// Parse args
		$route = $item['route'];
		$override = $item['override'] ?? false;

		unset($item['route']);
		unset($item['override']);

		// Set default args
		$args = $this->mergeArray([
			'method' => Restful::READABLE,
			'action' => $this->camelcase($route),
			'access' => 'access'
		], $item);

		// Set default settings
		$args = $this->mergeArray(static::SETTINGS, $args);

		// Set default callbacks
		$callbacks = ['action', 'access'];
		foreach ($callbacks as $callback) {
			if ( !$this->hasObject('method', $this, $args[$callback]) ) {
				$args[$callback] = $callback;
			}
		}

	    $this->registerRoute($this->namespace, $route, $args, $override);
	}

	/**
	 * Restrict REST by rules.
	 * [ip, user, role, cap].
	 *
	 * @access protected
	 * @return bool
	 */
	protected function restrictByRules(array $rules = []) : bool
	{
		// Set restricted rules
		$rules = $this->mergeArray([
			'user'   => false,
			'role'   => false,
			'cap'    => false,
			'ip'     => false
		], $rules);

		// Restrict ip
		if ( $rules['ip'] ) {

			if ( !($ip = $this->getServerIp()) ) {
				return true;
			}

			$restrict = $rules['ip'];
			if ( !$this->isType('array', $restrict) ) {
				$restrict = (string)$restrict;
				$restrict = [$restrict];
			}

			if ( $this->inArray($ip, $restrict) ) {
				return true;
			}

		}

		// Restrict user, role, cap
		if ( $rules['user'] || $rules['role'] || $rules['cap'] ) {

			if ( !($id = $this->getUserByAuth()) ) {
				return true;
			}

			// Restrict user
			if ( $rules['user'] ) {

				$restrict = $rules['user'];
				if ( !$this->isType('array', $restrict) ) {
					$restrict = (int)$restrict;
					$restrict = [$restrict];
				}

				if ( $this->inArray($id, $restrict) ) {
					return true;
				}

			}

			// Restrict role
			if ( $rules['role'] ) {

				$restrict = $rules['role'];
				if ( !$this->isType('array', $restrict) ) {
					$restrict = (string)$restrict;
					$restrict = [$restrict];
				}
				
				foreach ($restrict as $role) {
					if ( $this->hasRole($role, $id) ) {
						return true;
					}
				}

			}

			// Restrict user without cap
			if ( $rules['cap'] ) {

				$cap = (string)$rules['cap'];
				if ( !$this->hasCap($cap, $id) ) {
					return true;
				}

			}

			return false;
		}

		return false;
	}

	/**
	 * Get authorization status.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function isAuthorized() : bool
	{
		if ( $this->auth == 'token' ) {
			return $this->doTokenAuth();
		}

		if ( $this->auth == 'basic' ) {
			return $this->doAuth();
		}

		if ( $this->auth == 'any' ) {
			return ($this->doAuth() || $this->doTokenAuth());
		}

		return false;
	}

	/**
	 * Get authentication status.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function isAuthenticated() : bool
	{
		if ( $this->getBearerToken() ) {
			return true;
		}

		if ( $this->isBasicAuth() ) {
			return true;
		}

		return false;
	}

	/**
	 * Authenticate using "Bearer" token.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function doTokenAuth() : bool
	{
		if ( ($token = $this->getBearerToken()) ) {

			$secret = $this->getPluginSecret();
			$access = $this->getAccess($token, $secret);
			$user   = (string)$access['user'];
			$pswd   = (string)$access['pswd'];

			// Try authentication
			$auth = $this->authenticate($user, $pswd);

			if ( !$this->isError($auth) ) {
				return true;
			}

		}

		return false;
	}

	/**
	 * Authenticate using "Basic" auth.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function doAuth() : bool
	{
		if ( $this->isBasicAuth() ) {

			// Try authentication
			$user = $this->getBasicAuthUser();
			$pswd = $this->getBasicAuthPwd();
			$auth = $this->authenticate($user, $pswd);

			if ( !$this->isError($auth) ) {
				return true;
			}

		}

		return false;
	}

	/**
	 * Get user Id by auth.
	 *
	 * @access protected
	 * @return int
	 */
	protected function getUserByAuth() : int
	{
		$id = 0;

		if ( $this->isBasicAuth() ) {

			$login = $this->getBasicAuthUser();
			$user  = $this->getUserByLogin($login);

			if ( isset($user['id']) ) {
				$id = (int)$user['id'];
			}
		}

		if ( ($token = $this->getBearerToken()) ) {

			$secret = $this->getPluginSecret();
			$access = $this->getAccess($token, $secret);

			$email  = (string)$access['user'];
			$user   = $this->getUserByEmail($email);
			$id     = (int)$user['id'];

		}

		return $id;
	}
}
