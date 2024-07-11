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

namespace VanillePlugin\inc;

final class Globals
{
	/**
	 * Get site name.
	 *
	 * @access public
	 * @return string
	 */
	public static function website() : string
	{
		return get_bloginfo('name');
	}

	/**
	 * Get site email.
	 *
	 * @access public
	 * @return string
	 */
	public static function email() : string
	{
		return get_bloginfo('admin_email');
	}

	/**
	 * Get site version.
	 *
	 * @access public
	 * @return string
	 */
	public static function version() : string
	{
		global $wp_version;
		return $wp_version;
	}
	/**
	 * Get website scripts.
	 *
	 * @access public
	 * @return object
	 */
	public static function scripts()
	{
		global $wp_scripts;
		return $wp_scripts;
	}
	
	/**
	 * Get website styles.
	 *
	 * @access public
	 * @return object
	 */
	public static function styles()
	{
		global $wp_styles;
		return $wp_styles;
	}

	/**
	 * Get site debug status.
	 *
	 * @access public
	 * @return bool
	 */
	public static function debug() : bool
	{
		return defined('WP_DEBUG') && (WP_DEBUG == true);
	}

	/**
	 * Get site cache status.
	 *
	 * @access public
	 * @return bool
	 */
	public static function cache() : bool
	{
		return defined('WP_CACHE') && (WP_CACHE == true);
	}

	/**
	 * Check whether multisite is enabled.
	 *
	 * @access public
	 * @return bool
	 */
	public static function multisite() : bool
	{
		return is_multisite();
	}

	/**
	 * Check mobile.
	 *
	 * @access public
	 * @return bool
	 */
	public static function mobile() : bool
	{
		return wp_is_mobile();
	}
	
	/**
	 * Check ajax.
	 *
	 * @access public
	 * @return bool
	 */
	public static function ajax() : bool
	{
		$request = Server::get('request-uri');
		return Stringify::contains($request, 'admin-ajax.php');
	}

	/**
	 * Check REST API endpoint.
	 *
	 * @access public
	 * @return bool
	 */
	public static function api() : bool
	{
		return wp_is_rest_endpoint();
	}

	/**
	 * Get site roles.
	 *
	 * @access public
	 * @return array
	 */
	public static function roles() : array
	{
		return Arrayify::keys(wp_roles()->roles);
	}

	/**
	 * Get web server name.
	 *
	 * @access public
	 * @return string
	 */
	public static function server() : string
	{
		global $is_apache, $is_nginx, $is_iis7, $is_IIS;

		if ( $is_apache ) {
			return 'Apache';

		} elseif ( $is_nginx ) {
			return 'Nginx';

		} elseif ( $is_iis7 ) {
			return 'IIS 7';

		} elseif ( $is_IIS ) {
			return 'IIS';
		}

		return 'Unknown';
	}

	/**
	 * Get plugins URL without trailing slash.
	 *
	 * @access public
	 * @return string
	 */
	public static function pluginUrl() : string
	{
		return WP_PLUGIN_URL;
	}

	/**
	 * Get plugins directory without trailing slash,.
	 *
	 * @access public
	 * @return string
	 */
	public static function pluginDir() : string
	{
		return WP_PLUGIN_DIR;
	}

	/**
	 * Get MU plugins directory without trailing slash.
	 *
	 * @access public
	 * @return string
	 */
	public static function pluginMuDir() : string
	{
		return WPMU_PLUGIN_URL;
	}

	/**
	 * Get content URL without trailing slash.
	 *
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function contentUrl(?string $path = null) : string
	{
		$baseUrl = WP_CONTENT_URL;
		if ( $path ) {
			return Stringify::formatPath("{$baseUrl}/{$path}", true);
		}
		return $baseUrl;
	}
	
	/**
	 * Get content directory without trailing slash.
	 *
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function contentDir(?string $path = null) : string
	{
		$baseDir = WP_CONTENT_DIR;
		if ( $path ) {
			return Stringify::formatPath("{$baseDir}/{$path}", true);
		}
		return $baseDir;
	}

	/**
	 * Get root directory without trailing slash.
	 *
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function rootDir(?string $path = null) : string
	{
		$baseDir = ABSPATH;
		if ( $path ) {
			return Stringify::formatPath("{$baseDir}/{$path}", true);
		}
		return $baseDir;
	}

	/**
	 * Get front url.
	 *
	 * @access public
	 * @param string $path
	 * @param string $scheme
	 * @return string
	 */
	public static function url(?string $path = null, ?string $scheme = null) : string
	{
		$url = home_url((string)$path, $scheme);
		return Stringify::formatPath($url);
	}

	/**
	 * Get front site url,
	 * Including installation sub path.
	 *
	 * @access public
	 * @param string $path
	 * @param string $scheme
	 * @return string
	 */
	public static function siteUrl(?string $path = null, string $scheme = 'relative') : string
	{
		$url = site_url((string)$path, $scheme);
		return Stringify::formatPath($url);
	}

	/**
	 * Get site domain name.
	 *
	 * @access public
	 * @return string
	 */
	public static function siteDomain() : string
	{
		return Server::getDomain(
			self::siteUrl()
		);
	}

	/**
	 * Get admin url.
	 *
	 * @access public
	 * @param string $path
	 * @param string $scheme
	 * @return string
	 */
	public static function adminUrl(?string $path = null, string $scheme = 'admin') : string
	{
		$url = admin_url((string)$path, $scheme);
		return Stringify::formatPath($url);
	}

	/**
	 * Get includes url.
	 *
	 * @access public
	 * @param string $path
	 * @param string $scheme
	 * @return string
	 */
	public static function includesUrl(?string $path = null, string $scheme = 'admin') : string
	{
		$url = includes_url((string)$path, $scheme);
		return Stringify::formatPath($url);
	}

	/**
	 * Get REST url.
	 *
	 * @access public
	 * @param string $path
	 * @param string $scheme
	 * @return string
	 */
	public static function restUrl(?string $path = null, string $scheme = 'rest')
	{
		return get_rest_url(null, $path, $scheme);
	}

	/**
	 * Get ajax url.
	 *
	 * @access public
	 * @param string $scheme
	 * @return string
	 */
	public static function ajaxUrl(string $scheme = 'admin')
	{
		return self::adminUrl('admin-ajax.php', $scheme);
	}

	/**
	 * Get login URL.
	 *
	 * @access public
	 * @param string $redirect
	 * @param bool $auth
	 * @return string
	 */
	public static function loginUrl(?string $redirect = null, bool $auth = false) : string
	{
		return wp_login_url($redirect, $auth);
	}

	/**
	 * Get password reset URL.
	 *
	 * @access public
	 * @param string $redirect
	 * @return string
	 */
	public static function resetUrl(?string $redirect = null) : string
	{
		return wp_lostpassword_url($redirect);
	}
	
	/**
	 * Get privacy URL.
	 *
	 * @access public
	 * @return string
	 */
	public static function privacyUrl() : string
	{
		return get_privacy_policy_url();
	}
}
