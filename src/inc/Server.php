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

namespace VanillePlugin\inc;

final class Server
{
	/**
	 * Get global server variable.
	 *
	 * @access public
	 * @param string $item
	 * @param bool $format
	 * @return mixed
	 */
	public static function get($item = null, $format = true)
	{
		if ( $item ) {
			if ( $format ) {
				$item = self::formatArgs($item);
			}
			return self::isSetted($item) ? $_SERVER[$item] : null;
		}
		return self::isSetted() ? $_SERVER : null;
	}

	/**
	 * Set global server variable.
	 *
	 * @access public
	 * @param string $item
	 * @param mixed $value
	 * @param bool $format
	 * @return void
	 */
	public static function set($item, $value = null, $format = true)
	{
		if ( $format ) {
			$value = self::formatArgs($value);
		}
		$_SERVER[$item] = $value;
	}

	/**
	 * Check is global server variable setted.
	 *
	 * @access public
	 * @param string $item
	 * @param bool $format
	 * @return bool
	 */
	public static function isSetted($item = null, $format = true)
	{
		if ( $item ) {
			if ( $format ) {
				$item = self::formatArgs($item);
			}
			return isset($_SERVER[$item]);
		}
		return isset($_SERVER) && !empty($_SERVER);
	}
	
	/**
	 * Get remote IP address.
	 *
	 * @access public
	 * @param string $domain
	 * @return mixed
	 */
	public static function getIP($domain = null)
	{
		if ( $domain ) {
			$ip = gethostbyname($domain);
			return Validator::isValidIP($ip);
		}
		if ( self::isSetted('http-x-real-ip') ) {
			$ip = self::get('http-x-real-ip');
			return Stringify::stripSlash($ip);

		} elseif ( self::isSetted('http-x-forwarded-for') ) {
			$ip = self::get('http-x-forwarded-for');
			$ip = Stringify::stripSlash($ip);
			$ip = Stringify::split($ip, ['regex' => '/,/']);
			$ip = (string) trim(current($ip));
 			return Validator::isValidIP($ip);

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
	 * @param void
	 * @return string
	 */
	public static function getProtocol()
	{
		return self::isSSL() ? 'https://' : 'http://';
	}

	/**
	 * Get country code from request headers.
	 *
	 * @access public
	 * @param void
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
	 * Redirect URL.
	 *
	 * @access public
	 * @param string $url
	 * @param int $code
	 * @param string $message
	 * @return void
	 */
	public static function redirect($url = '/', $code = 301, $message = 'Moved Permanently')
	{
		header("Status: {$code} {$message}",false,$code);
		header("Location: {$url}");
		exit();
	}

	/**
	 * Get base URL.
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function getBaseUrl()
	{
		$url = self::get('http-host');
		if ( self::isSSL() ) {
			return "https://{$url}";
		} else {
			return "http://{$url}";
		}
	}

	/**
	 * Get current URL.
	 *
	 * @access public
	 * @param bool $escape
	 * @return string
	 */
	public static function getCurrentUrl($escape = false)
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
	public static function isDown($url = '', $args = [])
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
				'sslverify'   => self::isSSL()
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
		$request = new API();
		$request->setMethod($args['method']);
		$request->setArgs($args['http']);
		$request->setBaseUrl($url);

		// Set Auth
		if ( $args['auth'] ) {
			if ( TypeCheck::isArray($args['auth']) ) {
				$user = $args['auth'][0] ?? '';
				$pswd = $args['auth'][1] ?? '';
				$request->setBasicAuthentication($user,$pswd);
			} else {
				$request->setAuthentication($args['auth']);
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
	 * Check is authenticated.
	 *
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isBasicAuth()
	{
		if ( self::isSetted('php-auth-user') && self::isSetted('php-auth-pw') ) {
			return true;
		}
		return false;
	}

	/**
	 * Get authentication user.
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function getBasicAuthUser()
	{
		return self::isSetted('php-auth-user') ? self::get('php-auth-user') : '';
	}

	/**
	 * Get authentication password.
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function getBasicAuthPwd()
	{
		return self::isSetted('php-auth-pw') ? self::get('php-auth-pw') : '';
	}

	/**
	 * Get authorization header.
	 *
	 * @access private
	 * @param void
	 * @return mixed
	 */
	public static function getAuthorizationHeaders()
	{
        if ( self::isSetted('Authorization',false) ) {
            return trim(self::get('Authorization',false));

        } elseif ( self::isSetted('http-authorization') ) {
            return trim(self::get('http-authorization'));

        } elseif ( function_exists('apache_request_headers') ) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = Arrayify::combine(
            	Arrayify::map('ucwords',Arrayify::keys($requestHeaders)),
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
     * @access private
     * @param void
     * @return mixed
     */
    public static function getBearerToken()
    {
        if ( ($headers = self::getAuthorizationHeaders()) ) {
            return Stringify::match('/Bearer\s(\S+)/',$headers,1);
        }
        return false;
    }

	/**
	 * Check protocol is HTTPS (SSL).
	 *
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isSSL()
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
    public static function maybeRequireSSL($args)
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

	/**
	 * Format args.
	 *
	 * @access private
	 * @param string $arg
	 * @return string
	 * @internal
	 */
	private static function formatArgs($arg)
	{
	    $arg = Stringify::replace('-','_',$arg);
	    return Stringify::uppercase($arg);
	}
}
