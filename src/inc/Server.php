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

namespace VanillePlugin\inc;

final class Server
{
	/**
	 * Get _SERVER value.
	 * 
	 * @access public
	 * @param string $key
	 * @param bool $format
	 * @return mixed
	 */
	public static function get(?string $key = null, $format = true)
	{
		if ( $key ) {
			if ( $format ) {
				$key = Stringify::undash($key, true);
			}
			return self::isSetted($key) ? $_SERVER[$key] : null;
		}
		return self::isSetted() ? $_SERVER : null;
	}

	/**
	 * Set _SERVER value.
	 * 
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param bool $format
	 * @return void
	 */
	public static function set(string $key, $value = null, $format = true)
	{
		if ( $format ) {
			$value = Stringify::undash($key, true);
		}
		$_SERVER[$key] = $value;
	}

	/**
	 * Check _SERVER value.
	 * 
	 * @access public
	 * @param string $key
	 * @param bool $format
	 * @return bool
	 */
	public static function isSetted(?string $key = null, $format = true) : bool
	{
		if ( $key ) {
			if ( $format ) {
				$key = Stringify::undash($key, true);
			}
			return isset($_SERVER[$key]);
		}
		return isset($_SERVER) && !empty($_SERVER);
	}

	/**
	 * Unset _SERVER value.
	 * 
	 * @access public
	 * @param string $key
	 * @return void
	 */
	public static function unset(?string $key = null)
	{
		if ( $key ) {
			unset($_SERVER[$key]);

		} else {
			$_SERVER = [];
		}
	}

	/**
	 * Get remote IP address.
	 *
	 * @access public
	 * @param string $domain
	 * @return mixed
	 */
	public static function getIp(?string $domain = null)
	{
		if ( $domain ) {
			$ip = gethostbyname($domain);
			return Validator::isValidIp($ip);
		}

		if ( self::isSetted('http-x-real-ip') ) {
			$ip = self::get('http-x-real-ip');
			return Stringify::stripSlash($ip);

		} elseif ( self::isSetted('http-x-forwarded-for') ) {
			$ip = self::get('http-x-forwarded-for');
			$ip = Stringify::stripSlash($ip);
			$ip = Stringify::split($ip, ['regex' => '/,/']);
			$ip = (string)trim(current($ip));
 			return Validator::isValidIp($ip);

		} elseif ( self::isSetted('http-cf-connecting-ip') ) {
			$ip = self::get('http-cf-connecting-ip');
			$ip = Stringify::stripSlash($ip);
			$ip = Stringify::split($ip, ['regex' => '/,/']);
			$ip = (string)trim(current($ip));
 			return Validator::isValidIp($ip);

		} elseif ( self::isSetted('remote-addr') ) {
			$ip = self::get('remote-addr');
			return Stringify::stripSlash($ip);
		}

		return false;
	}

	/**
	 * Get prefered protocol.
	 *
	 * @access public
	 * @return string
	 */
	public static function getProtocol()
	{
		return self::isSsl() ? 'https://' : 'http://';
	}

	/**
	 * Get country code from request headers.
	 *
	 * @access public
	 * @return string
	 */
	public static function getCountryCode()
	{
		$headers = [
			'mm-country-code',
			'geoip-country-code',
			'http-cf-ipcountry',
			'http-x-country-code'
		];
		foreach ($headers as $header) {
			if ( self::isSetted($header) ) {
				$code = self::get($header);
				if ( !empty($code) ) {
					$code = Stringify::stripSlash($code);
					return Stringify::uppercase($code);
					break;
				}
			}
		}
		return false;
	}
	
	/**
	 * Redirect request.
	 *
	 * @access public
	 * @param string $location
	 * @param int $status
	 * @return void
	 */
	public static function redirect(string $location, int $status = 301)
	{
		wp_redirect($location, $status);
		exit();
	}

	/**
	 * Get base URL.
	 *
	 * @access public
	 * @return string
	 */
	public static function getBaseUrl() : string
	{
		$url = self::get('http-host');
		if ( self::isSsl() ) {
			return "https://{$url}";
		}
		return "http://{$url}";
	}

	/**
	 * Get current URL.
	 *
	 * @access public
	 * @param bool $escape
	 * @return string
	 */
	public static function getCurrentUrl($escape = false) : string
	{
		$url = self::getBaseUrl() . self::get('request-uri');
		if ( $escape ) {
			$url = Stringify::parseUrl($url);
			if ( isset($url['query']) ) {
				unset($url['query']);
			}
			$url = rtrim("{$url['scheme']}://{$url['host']}{$url['path']}");
		}
		return $url;
	}

	/**
	 * Parse base from URL.
	 *
	 * @access public
	 * @param string $url
	 * @return string
	 */
	public static function parseBaseUrl($url = '')
	{
		if ( !empty($url) && ($url = Stringify::parseUrl($url)) ) {
			unset($url['path']);
			$tmp = '';
			if ( isset($url['scheme']) ) {
				$tmp = "{$url['scheme']}://";
			}
			if ( isset($url['host']) ) {
				$tmp = "{$tmp}{$url['host']}";
			}
			$url = $tmp;
		}
		return (string)$url;
	}

	/**
	 * Check external server status code.
	 * 
	 * @access public
	 * @param string $url
	 * @param array $args
	 * @return bool
	 */
	public static function isDown(string $url, $args = [])
	{
		// Set overridden args
		$args = Arrayify::merge([
			'code'     => 500,
			'format'   => false,
			'endpoint' => true,
			'response' => false,
			'auth'     => false,
			'method'   => 'GET',
			'operator' => '>=',
			'http'     => [
				'timeout'     => 30,
				'redirection' => 0,
				'sslverify'   => self::isSsl()
			]
		], self::maybeRequireSSL($args));

		// Format URL (Base)
		if ( $args['format'] ) {
			$url = self::parseBaseUrl($url);
		}

		// Format URL (Endpoint)
		if ( $args['endpoint'] ) {
			$url = rtrim($url,'/');
			$url = "{$url}/";
		}

		// Init request
		$request = new Request();
		$request->setMethod($args['method']);
		$request->setArgs($args['http']);
		$request->setBaseUrl($url);

		// Set Auth
		if ( $args['auth'] ) {
			if ( TypeCheck::isArray($args['auth']) ) {
				$user = $args['auth'][0] ?? '';
				$pswd = $args['auth'][1] ?? '';
				$token = Tokenizer::base64("{$user}:{$pswd}");
				$request->addHeader(
					'Authorization',
					sprintf('Basic %s', $token)
				);

			} else {
				$request->addHeader(
					'Authorization',
					sprintf('Bearer %s', $args['auth'])
				);
			}
		}

		// Send request
		$request->send();

		// Check status match
		if ( !$request->getStatusCode() ) {
			return true;
		}

		if ( $args['operator'] == '==' ) {
			if ( $request->getStatusCode() == intval($args['code']) ) {
				return true;
			}

		} elseif ( $args['operator'] == '>=' ) {
			if ( $request->getStatusCode() >= intval($args['code']) ) {
				return true;
			}

		} elseif ( $args['operator'] == '<=' ) {
			if ( $request->getStatusCode() <= intval($args['code']) ) {
				return true;
			}
		}

		// Check response match
		if ( $args['response'] ) {
			if ( ($body = Json::decode($request->getBody(),true)) ) {
				if ( TypeCheck::isString($args['response']) ) {
					if ( !isset($body[$args['response']]) ) {
						return true;
					}
				}
			} else {
				if ( $request->getBody() !== $args['response'] ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Check basic authentication.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isBasicAuth() : bool
	{
		if ( self::isSetted('php-auth-user') && self::isSetted('php-auth-pw') ) {
			return true;
		}
		return false;
	}

	/**
	 * Get basic authentication user.
	 *
	 * @access public
	 * @return string
	 */
	public static function getBasicAuthUser() : string
	{
		return self::isSetted('php-auth-user') ? self::get('php-auth-user') : '';
	}

	/**
	 * Get get basic authentication password.
	 *
	 * @access public
	 * @return string
	 */
	public static function getBasicAuthPwd() : string
	{
		return self::isSetted('php-auth-pw') ? self::get('php-auth-pw') : '';
	}

	/**
	 * Get authorization header.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getAuthorizationHeaders()
	{
        if ( self::isSetted('Authorization', false) ) {
            return trim(self::get('Authorization', false));

        } elseif ( self::isSetted('http-authorization') ) {
            return trim(self::get('http-authorization'));

        } elseif ( function_exists('apache_request_headers') ) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = Arrayify::combine(
            	Arrayify::map('ucwords', Arrayify::keys($requestHeaders)),
            	Arrayify::values($requestHeaders)
            );
            if ( isset($requestHeaders['Authorization']) ) {
                return trim($requestHeaders['Authorization']);
            }
        }
        return false;
    }

	/**
	 * Get authorization token.
	 *
     * @access public
     * @return string
     */
    public static function getBearerToken() : string
    {
		$token = false;
        if ( ($headers = self::getAuthorizationHeaders()) ) {
            $token = Stringify::match('/Bearer\s(\S+)/', $headers, 1);
        }
        return (string)$token;
    }

	/**
	 * Check whether protocol is HTTPS (SSL).
	 *
	 * @access public
	 * @return bool
	 */
	public static function isSsl() : bool
	{
		return is_ssl();
	}

    /**
     * Check if SSL verify is required in request,
     * Maybe remote server requires (SNI),
     * Fix (SNI) SSL verification.
     *
     * @access public
     * @param array $args, Request args
     * @return array
     */
    public static function maybeRequireSSL(array $args) : array
    {
    	$hasCurl = TypeCheck::isFunction('curl_init');
		if ( isset($args['sslverify']) && !$args['sslverify'] ) {
			// Force sslverify when cUrl not used
			if ( !$hasCurl ) {
				$args['sslverify'] = true;
			}
		}
    	return $args;
    }
}
