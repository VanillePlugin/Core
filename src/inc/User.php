<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

use \WP_User;

final class User
{
	/**
	 * Get user by Id.
	 * 
	 * @access public
	 * @param mixed $id
	 * @param bool $format
	 * @return mixed
	 */
	public static function get($id = null, bool $format = true)
	{
        if ( $id ) {
            return self::getById($id, $format);
        }
		return self::current($format);
	}

	/**
	 * Get current user Id.
	 * 
	 * @access public
	 * @return int
	 */
	public static function getId() : int
	{
		$user = self::current();
		return $user->ID ?? 0;
	}

	/**
     * Get user email by Id.
     * 
	 * @access public
	 * @param mixed $id
	 * @return mixed
	 */
	public static function getEmail($id = null)
	{
		if ( !$id ) $id = self::getId();
		$user = self::getBy('ID', (int)$id);
		return $user['email'] ?? false;
	}

	/**
     * Get user hash by Id.
     * 
	 * @access public
	 * @param mixed $id
	 * @return mixed
	 */
	public static function getHash($id = null)
	{
		if ( !$id ) $id = self::getId();
		$user = self::getBy('ID', (int)$id);
		return $user['hash'] ?? false;
	}

	/**
	 * Get current user.
	 * 
	 * @access public
     * @param bool $format
	 * @return mixed
	 */
	public static function current(bool $format = true)
	{
		$user = wp_get_current_user();
        if ( $format ) {
            return self::format($user);
        }
        return $user;
	}

	/**
     * Get user by field.
     * 
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param bool $format
	 * @return mixed
	 */
	public static function getBy(string $key, $value, bool $format = true)
	{
        $user = get_user_by($key, $value);
        if ( $format ) {
            return self::format($user);
        }
        return $user;
	}

	/**
     * Get user by Id.
     * 
	 * @access public
	 * @param mixed $id
	 * @param bool $format
	 * @return mixed
	 */
	public static function getById($id = null, bool $format = true)
	{
		if ( !$id ) $id = self::getId();
		return self::getBy('ID', (int)$id, $format);
	}

	/**
	 * Get user by email.
	 * 
	 * @access public
	 * @param string $email
	 * @param bool $format
	 * @return mixed
	 */
	public static function getByEmail(string $email, bool $format = true)
	{
		return self::getBy('email', $email, $format);
	}

	/**
     * Get users by meta.
     * 
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param bool $format
	 * @return array
	 */
	public static function getByMeta(string $key, $value, bool $format = true) : array
	{
		$users = get_users([
			'meta_key'   => $key,
			'meta_value' => $value
		]);
        if ( $format ) {
            $wrapper = [];
            foreach ($users as $user) {
                $wrapper[] = self::format($user);
            }
			return $wrapper;
		}
		return $users;
	}

	/**
	 * Get all users.
	 * 
	 * @access public
	 * @param bool $format
	 * @return array
	 */
	public static function all(bool $format = true) : array
	{
		$users = get_users();
        if ( $format ) {
            $wrapper = [];
            foreach ($users as $user) {
                $wrapper[] = self::format($user);
            }
            return $wrapper;
        }
        return $users;
	}

	/**
	 * Check whether user exists.
	 *
	 * @access public
	 * @param mixed $user
	 * @param string $property
	 * @return bool
	 */
	public static function isUser($user, string $property = 'username') : bool
	{
		switch ( Stringify::lowercase($property) ) {
			case 'username':
				return self::hasLogin((string)$user);
				break;

			case 'email':
				return self::hasEmail((string)$user);
				break;

			case 'id':
				return self::exists($user);
				break;
		}
		return false;
	}

	/**
     * Check user email.
     * 
	 * @access public
	 * @param string $email
	 * @return bool
	 */
	public static function hasEmail(string $email) : bool
	{
		return (bool)email_exists($email);
	}

	/**
     * Check user login.
     * 
	 * @access public
	 * @param string $login
	 * @return bool
	 */
	public static function hasLogin(string $login) : bool
	{
		return (bool)username_exists($login);
	}

	/**
     * Check user login.
     * 
	 * @access public
	 * @param mixed $id
	 * @return bool
	 */
	public static function exists($id) : bool
	{
		$user = new WP_User($id);
		return $user->exists();
	}

	/**
	 * Add user.
	 * 
	 * @access public
	 * @param mixed $data
	 * @param bool $error
	 * @param bool $after
	 * @return mixed
	 */
	public static function add($data, bool $error = false, bool $after = true)
	{
		return wp_insert_user($data, $error, $after);
	}

	/**
	 * Update user.
	 * 
	 * @access public
	 * @param mixed $data
	 * @param bool $error
	 * @param bool $after
	 * @return mixed
	 */
	public static function update($data, bool $error = false, bool $after = true)
	{
		return wp_update_user($data, $error, $after);
	}

	/**
	 * Delete user.
	 * 
	 * @access public
	 * @param mixed $id
	 * @param bool $force
	 * @return bool
	 */
	public static function delete($id = null, bool $force = false) : bool
	{
		if ( !$id ) $id = self::getId();
		return (bool)wp_delete_user((int)$id, $force);
	}

	/**
     * Add meta.
     * 
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param mixed $id
	 * @param bool $unique
	 * @return mixed
	 */
	public static function addMeta(string $key, $value, $id = null, bool $unique = false)
	{
		if ( !$id ) $id = self::getId();
		return add_user_meta((int)$id, $key, $value, $unique);
	}

	/**
     * Get meta.
     * 
	 * @access public
	 * @param string $key
	 * @param mixed $id
	 * @param bool $single
	 * @return mixed
	 */
	public static function getMeta(string $key, $id = null, bool $single = true)
	{
		if ( !$id ) $id = self::getId();
		return get_user_meta((int)$id, $key, $single);
	}

	/**
     * Update meta.
     * 
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param mixed $id
	 * @return mixed
	 */
	public static function updateMeta(string $key, $value, $id = null)
	{
		if ( !$id ) $id = self::getId();
		return update_user_meta((int)$id, $key, $value);
	}

	/**
     * Delete meta.
     * 
	 * @access public
	 * @param string $key
	 * @param mixed $id
	 * @param mixed $value
	 * @return bool
	 */
	public static function deleteMeta(string $key, $id = null, $value = null) : bool
	{
		if ( !$id ) $id = self::getId();
		return delete_user_meta((int)$id, $key, $value);
	}

	/**
     * Register user.
     * 
	 * @access public
	 * @param string $email
	 * @param string $pswd
	 * @param string $user
	 * @return mixed
	 */
	public static function register(string $email, ?string $pswd = null, ?string $user = null)
	{
		if ( !$pswd ) $pswd = Password::generate();
		if ( !$user ) $user = 'user-' . Tokenizer::generate(8);

		$id = wp_create_user($user, $pswd, $email);
		if ( !Exception::isError($id) ) {
			return $id;
		}

		return false;
	}

	/**
     * Login user.
     * 
	 * @access public
	 * @param string $user
	 * @param string $pswd
	 * @param bool $memory
	 * @return bool
	 */
	public static function login(string $user, string $pswd, bool $memory = false) : bool
	{
		$login = wp_signon([
			'user_login'    => $user,
			'user_password' => $pswd,
			'remember'      => $memory
		], Server::isSsl());

		if ( !Exception::isError($login) ) {
			return true;
		}

		return false;
	}

	/**
	 * Authenticate user.
	 *
	 * @access public
	 * @param string $user
	 * @param string $pswd
	 * @return mixed
	 */
	public static function authenticate(string $user, string $pswd)
	{
		return wp_authenticate($user, $pswd);
	}

	/**
     * Logout user.
     * 
	 * @access public
	 * @return void
	 */
	public static function logout()
	{
		wp_destroy_current_session();
		wp_clear_auth_cookie();
		wp_set_current_user(0);
	}

	/**
     * Validate user password.
     * 
	 * @access public
	 * @param string $pswd
	 * @param string $hash
	 * @param mixed $id
	 * @return bool
	 */
	public static function isPassword(string $pswd, string $hash, $id = null) : bool
	{
		if ( !$id ) $id = self::getId();
		return Password::isValid($pswd, $hash, $id);
	}

	/**
     * Send user password.
     * 
	 * @access public
	 * @param mixed $id
	 * @return bool
	 */
	public static function sendPassword($id = null) : bool
	{
		if ( !$id ) $id = self::getId();
        if ( ($user = self::getById((int)$id)) ) {
           return Password::send($user['login']);
        }
		return false;
	}

	/**
	 * Check whether user is logged-in.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isLoggedIn() : bool
	{
		return is_user_logged_in();
	}

	/**
	 * Get user roles.
	 *
	 * @access public
	 * @param mixed $id
	 * @return array
	 */
	public static function getRoles($id = null) : array
	{
		if ( !$id ) $id = self::getId();
		$user = new WP_User($id);
		return (array)$user->roles;
	}

	/**
	 * Get user capabilities.
	 *
	 * @access public
	 * @param mixed $id
	 * @return array
	 */
	public static function getCaps($id = null) : array
	{
		if ( !$id ) $id = self::getId();
		$user = new WP_User($id);
		return (array)$user->caps;
	}

	/**
	 * Get role.
	 *
	 * @access public
	 * @param string $role
	 * @return mixed
	 */
	public static function getRole(string $role)
	{
		return get_role($role);
	}

	/**
	 * Add role(s) to user.
	 *
	 * @access public
	 * @param string $display
	 * @param string $role
	 * @param array $cap
	 * @return mixed
	 */
	public static function addRole(string $display, string $role = null, array $cap = [])
	{
		$role = ($role) ? (string)$role : $display;
		$role = Stringify::slugify($role);
		$role = Stringify::undash($role);
		return add_role($role, $display, $cap);
	}

	/**
	 * Remove role.
	 *
	 * @access public
	 * @param string $role
	 * @return void
	 */
	public static function removeRole(string $role)
	{
		remove_role($role);
	}

	/**
	 * Add role capability.
	 *
	 * @access public
	 * @param string $role
	 * @param string $cap
	 * @param bool $grant
	 * @return bool
	 */
	public static function addCapability(string $role, string $cap, bool $grant = true) : bool
	{
		if ( ($role = self::getRole($role)) ) {
			$role->add_cap($cap, $grant);
			return true;
		}
		return false;
	}

	/**
	 * Remove role capability.
	 *
	 * @access public
	 * @param string $role
	 * @param string $cap
	 * @return bool
	 */
	public static function removeCapability(string $role, string $cap) : bool
	{
		if ( ($role = self::getRole($role)) ) {
			$role->remove_cap($cap);
			return true;
		}
		return false;
	}

	/**
	 * Check whether current user has capability.
	 *
	 * @access public
	 * @param string $cap
	 * @param mixed $args
	 * @return bool
	 */
	public static function hasCapability(string $cap = 'edit_posts', $args = null) : bool
	{
		return current_user_can($cap, $args);
	}

	/**
	 * Check user capability (Alias).
	 *
	 * @access public
	 * @param string $cap
	 * @param mixed $args
	 * @param mixed $id
	 * @return bool
	 */
	public static function hasPermission(string $cap = 'edit_posts', $args = null, $id = null) : bool
	{
		if ( !$id ) $id = self::getId();
		return user_can($id, $cap, $args);
	}

	/**
     * Get user formatted data.
     * 
	 * @access private
	 * @param mixed $user
	 * @return mixed
	 */
	private static function format($user)
	{
        if ( $user ) {
            return [
                'id'    => $user->data->ID,
                'login' => $user->data->user_login,
                'name'  => $user->data->user_nicename,
                'email' => $user->data->user_email,
                'hash'  => $user->data->user_pass
            ];
        }
        return $user;
	}
}
