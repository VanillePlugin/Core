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

final class Format
{
	/**
	 * Format hook.
	 *
	 * @access public
	 * @param string $hook
	 * @return string
	 */
	public static function hook(string $hook) : string
	{
        $name = Stringify::lowercase($hook);

		// Format dashed hooks
		$format = [
			'plugins-loaded',
			'plugins-api',
			'add-meta-boxes',
			'body-class',
			'user-register',
			'login-enqueue-scripts',
			'login-body-class',
			'login-form',
			'login-form-defaults',
			'login-url',
			'login-redirect',
			'admin-init',
			'admin-menu',
			'admin-bar-menu',
			'admin-enqueue-scripts',
			'admin-body-class',
			'admin-footer-text',
			'admin-notices',
			'show-admin-bar',
			'update-footer',
			'save-post',
			'parse-query',
			'sanitize-title',
			'sanitize-key',
			'sanitize-email',
			'sanitize-file-name',
			'widget-text',
			'author-link',
			'media-buttons',
			'template-redirect',
			'http-request-args',
			'cron-schedules',
			'upgrade-complete',
			'auto-update-plugin',
			'rest-api-init',
			'rest-namespace-index'
		];

		if ( Arrayify::inArray($name, $format)) {
			return Stringify::undash($name);
		}

		// Format custom hooks
		$format = [
			'loaded'              => 'wp_loaded',
			'setup'               => 'wp',
			'headers'             => 'wp_headers',
			'head'                => 'wp_head',
			'front-init'          => 'init',
			'template'            => 'template_redirect',
			'body'                => 'wp_body_open',
			'title'               => 'the_title',
			'content'             => 'the_content',
			'footer'              => 'wp_footer',
			'posts'               => 'pre_get_posts',
			'redirect'            => 'wp_redirect',
			'insert-post-data'    => 'wp_insert_post_data',
			'post-status'         => 'transition_post_status',
			'generator'           => 'the_generator',
			'search-form'         => 'get_search_form',
			'enqueue-scripts'     => 'wp_enqueue_scripts',
			'avatar'              => 'get_avatar',
			'user-auth'           => 'wp_authenticate_user',
			'login-header-url'    => 'login_headerurl',
			'login-header-text'   => 'login_headertext',
			'login-error'         => 'login_errors',
			'amp-css'             => 'amp_post_template_css',
			'amp-head'            => 'amp_post_template_head',
			'amp-footer'          => 'amp_post_template_footer',
			'upgrade-complete'    => 'upgrader_process_complete',
			'update-plugins'      => 'pre_set_site_transient_update_plugins',
			'update-themes'       => 'pre_set_site_transient_update_themes',
			'theme-setup'         => 'after_setup_theme',
			'print-styles'        => 'wp_print_styles',
			'print-scripts'       => 'wp_print_scripts',
			'plugin-row'          => 'plugin_row_meta',
			'mail'                => 'wp_mail',
			'mail-from'           => 'wp_mail_from',
			'mail-name'           => 'wp_mail_from_name',
			'dashboard-setup'     => 'wp_dashboard_setup',
			'widget-init'         => 'widgets_init',
			'allowed-protocols'   => 'kses_allowed_protocols',
			'allowed-html'        => 'wp_kses_allowed_html',
			'escape-url'          => 'clean_url',
			'escape-html'         => 'esc_html',
			'escape-xml'          => 'esc_xml',
			'escape-js'           => 'js_escape',
			'escape-attr'         => 'attribute_escape',
			'escape-textarea'     => 'esc_textarea',
			'sanitize-text'       => 'sanitize_text_field',
			'sanitize-textarea'   => 'sanitize_textarea_field',
			'sanitize-html'       => 'sanitize_html_class',
			'sanitize-mime'       => 'sanitize_mime_type',
			'sanitize-file-chars' => 'sanitize_file_name_chars',
			'sanitize-username'   => 'sanitize_user',
			'nonce-ttl'           => 'nonce_life',
			'rewrite-rules'       => 'mod_rewrite_rules',
			'xml-rpc'             => 'xmlrpc_enabled',
			'rest-api-prefix'     => 'rest_url_prefix',
			'rest-api-index'      => 'rest_index',
			'rest-api-endpoint'   => 'rest_endpoints',
			'rest-api-error'      => 'rest_authentication_errors',
			'rest-api-jsonp'      => 'rest_jsonp_enabled',
			'rest-api-request'    => 'rest_pre_serve_request',
			'rest-api-response'   => 'rest_pre_echo_response',
			'rest-api-dispatch'   => 'rest_pre_dispatch',
			'rest-api-callback'   => 'rest_request_before_callbacks',
			'rest-api-nocache'    => 'rest_send_nocache_headers',
			'app-password'        => 'wp_is_application_passwords_available',
			'app-password-user'   => 'wp_is_application_passwords_available_for_user',
			'user-profile'        => 'show_user_profile',
			'update-profile'      => 'personal_options_update'
		];

		if ( isset($format[$name]) ) {
			return $format[$name];
		}

		// Format prefixed hooks
		$format = [
			'wp-ajax-nopriv-',
			'wp-ajax-',
			'add-meta-boxes-',
			'sanitize-option-'
		];

		foreach ($format as $value) {
			if ( Stringify::contains($name, $value)) {
				$replace = Stringify::undash($value);
				return Stringify::replace($value, $replace, $name);
			}
		}

        return $hook;
	}

	/**
     * Get formatted request args.
     *
	 * @access public
	 * @param array $args
	 * @return array
	 */
	public static function request(array $args) : array
	{
		foreach ($args as $key => $value) {
			$k = Stringify::lowercase($key);
			if ( $k !== 'user-agent' ) {
				$k = Stringify::undash($k);
			}
			unset($args[$key]);
			$args[$k] = $value;
		}
		return $args;
	}

	/**
     * Get formatted restful args.
     *
	 * @access public
	 * @param array $args
	 * @return array
	 */
	public static function restful(array $args) : array
	{
		$format = [
			'method' => 'methods',
			'action' => 'callback',
			'access' => 'permission_callback'
		];

		foreach ($args as $key => $value) {

			if ( isset($format[$key]) ) {
				$k = $format[$key];
				unset($args[$key]);
				$args[$k] = $value;
				continue;
			}

			$k = Stringify::lowercase($key);
			if ( Stringify::contains($k, '-') ) {
				$k = Stringify::undash($k);
				unset($args[$key]);
				$args[$k] = $value;
			}
			
		}
		
		return $args;
	}

	/**
     * Format dashed args.
     *
	 * @access public
	 * @param array $args
	 * @return array
	 */
	public static function undash(array $args) : array
	{
		foreach ($args as $key => $value) {
			if ( Stringify::contains($key, '-') ) {
				$k = Stringify::undash($key);
				unset($args[$key]);
				$args[$k] = $value;
			}
		}
		return $args;
	}

	/**
     * Get post formatted data.
     *
	 * @access public
	 * @param mixed $post
	 * @return mixed
	 */
	public static function post($post)
	{
        if ( TypeCheck::isObject($post) ) {
            return [
                'id'      => $post->ID,
                'slug'    => $post->post_name,
                'title'   => $post->post_title,
                'content' => $post->post_content,
                'link'    => $post->guid,
                'type'    => Stringify::lowercase($post->post_type),
                'status'  => $post->post_status,
                'author'  => $post->post_author,
                'date'    => $post->post_date,
                'edited'  => $post->post_modified
            ];
        }
        return $post;
	}

	/**
     * Get user formatted data.
     *
	 * @access public
	 * @param mixed $user
	 * @return mixed
	 */
	public static function user($user)
	{
        if ( TypeCheck::isObject($user) ) {
			if ( !count((array)$user->data) ) {
				return [];
			}
			$name = $user->data->display_name;
			if ( empty($name) ) {
				$name = $user->data->user_nicename;
			}
            return [
                'id'    => (int)$user->data->ID,
                'login' => $user->data->user_login,
                'name'  => $name,
                'email' => $user->data->user_email,
                'hash'  => $user->data->user_pass
            ];
        }
        return $user;
	}
}
