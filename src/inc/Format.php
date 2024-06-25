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
		
        if ( Stringify::contains($name, 'wp-ajax-') ) {
			$hook = Stringify::replace('wp-ajax-nopriv-', 'wp_ajax_nopriv_', $hook);
			$hook = Stringify::replace('wp-ajax-', 'wp_ajax_', $hook);
			return $hook;
        }

		switch ( $name ) {
			case 'loaded':
				$hook = 'wp_loaded';
				break;
			case 'head':
				$hook = 'wp_head';
				break;
			case 'body':
				$hook = 'wp_body_open';
				break;
			case 'content':
				$hook = 'the_content';
				break;
			case 'footer':
				$hook = 'wp_footer';
				break;
			case 'enqueue-scripts':
				$hook = 'wp_enqueue_scripts';
				break;
			case 'body-class':
				$hook = 'body_class';
				break;
			case 'user-register':
				$hook = 'user_register';
				break;
			case 'user-auth':
				$hook = 'wp_authenticate_user';
				break;
			case 'login-enqueue-scripts':
				$hook = 'login_enqueue_scripts';
				break;
			case 'login-body-class':
				$hook = 'login_body_class';
				break;
			case 'login-header-url':
				$hook = 'login_headerurl';
				break;
			case 'login-header-text':
				$hook = 'login_headertext';
				break;
			case 'login-form':
				$hook = 'login_form';
				break;
			case 'login-form-defaults':
				$hook = 'login_form_defaults';
				break;
			case 'amp-css':
				$hook = 'amp_post_template_css';
				break;
			case 'amp-head':
				$hook = 'amp_post_template_head';
				break;
			case 'amp-footer':
				$hook = 'amp_post_template_footer';
				break;
			case 'plugins-loaded':
				$hook = 'plugins_loaded';
				break;
			case 'plugin-row':
				$hook = 'plugin_row_meta';
				break;
			case 'admin-init':
				$hook = 'admin_init';
				break;
			case 'admin-menu':
				$hook = 'admin_menu';
				break;
			case 'admin-bar-menu':
				$hook = 'admin_bar_menu';
				break;
			case 'show-admin-bar':
				$hook = 'show_admin_bar';
				break;
			case 'admin-enqueue-scripts':
				$hook = 'admin_enqueue_scripts';
				break;
			case 'admin-body-class':
				$hook = 'admin_body_class';
				break;
			case 'admin-footer-text':
				$hook = 'admin_footer_text';
				break;
			case 'admin-notices':
				$hook = 'admin_notices';
				break;
			case 'mail':
				$hook = 'wp_mail';
				break;
			case 'mail-from':
				$hook = 'wp_mail_from';
				break;
			case 'mail-name':
				$hook = 'wp_mail_from_name';
				break;
			case 'update-footer':
				$hook = 'update_footer';
				break;
			case 'save-post':
				$hook = 'save_post';
				break;
			case 'insert-post-data':
				$hook = 'wp_insert_post_data';
				break;
			case 'post-status':
				$hook = 'transition_post_status';
				break;
			case 'media-button':
				$hook = 'media_buttons';
				break;
			case 'template-redirect':
				$hook = 'template_redirect';
				break;
			case 'dashboard-setup':
				$hook = 'wp_dashboard_setup';
				break;
			case 'rest-api':
				$hook = 'rest_api_init';
				break;
			case 'http-request-args':
				$hook = 'http_request_args';
				break;
			case 'cron-schedules':
				$hook = 'cron_schedules';
				break;
			case 'upgrader-process-complete':
				$hook = 'upgrader_process_complete';
				break;
			case 'auto-update-plugin':
				$hook = 'auto_update_plugin';
				break;
			case 'plugins-api':
				$hook = 'plugins_api';
				break;
			case 'pre-transient-update-plugins':
				$hook = 'pre_set_site_transient_update_plugins';
				break;
			case 'pre-transient-update-themes':
				$hook = 'pre_set_site_transient_update_themes';
				break;
			case 'rewrite-rules':
				$hook = 'mod_rewrite_rules';
				break;
		}
        return $hook;
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
        if ( $post ) {
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
			$name = $user->data->display_name;
			if ( empty($name) ) {
				$name = $user->data->user_nicename;
			}
            return [
                'id'    => $user->data->ID,
                'login' => $user->data->user_login,
                'name'  => $name,
                'email' => $user->data->user_email,
                'hash'  => $user->data->user_pass
            ];
        }
        return $user;
	}
}
