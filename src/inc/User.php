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

namespace VanillePlugin\inc;

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
		if ( TypeCheck::isNull($id) ) {
			return self::current($format);
		}
        return self::getById($id, $format);
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
		$id = $user['id'] ?? 0;
		return (int)$id;
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
		if ( TypeCheck::isNull($id) ) {
			$id = self::getId();
		}
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
		if ( TypeCheck::isNull($id) ) {
			$id = self::getId();
		}
		$user = self::getBy('ID', (int)$id);
		return $user['hash'] ?? false;
	}

	/**
	 * Get user avatar.
	 *
	 * @access public
	 * @param mixed $id
	 * @return string
	 */
	public static function getAvatar($id = null) : string
	{
		if ( TypeCheck::isNull($id) ) {
			$id = self::getId();
		}
		return (string)get_avatar_url($id, [
			'default' => '404',
			'size'    => 100
		]);
	}

	/**
     * Get user data.
     *
	 * @access public
	 * @param string $key
	 * @param mixed $id
	 * @return mixed
	 */
	public static function getData(string $key, $id = null)
	{
		if ( TypeCheck::isNull($id) ) {
			$id = self::getId();
		}
		$id = (int)$id;
		if ( ($user = get_userdata($id)) ) {
			return $user->{$key} ?? false;
		}
		return false;
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
		return ($format) ? Format::user($user) : $user;
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
        return ($format) ? Format::user($user) : $user;
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
		if ( TypeCheck::isNull($id) ) {
			$id = self::getId();
		}
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
	 * Get user by login.
	 *
	 * @access public
	 * @param string $login
	 * @param bool $format
	 * @return mixed
	 */
	public static function getByLogin(string $login, bool $format = true)
	{
		return self::getBy('login', $login, $format);
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
		return self::all([
			'meta-key'   => $key,
			'meta-value' => $value
		], $format);
	}

	/**
	 * Get all users.
	 *
	 * @access public
	 * @param array $args
	 * @param bool $format
	 * @return array
	 */
	public static function all(array $args = [], bool $format = true) : array
	{
		$users = get_users(Format::undash($args));
        if ( $format ) {
			$users = Arrayify::map(function($user) {
				return Format::user($user);
			}, $users);
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
     * Check user exists by email.
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
     * Check user exists by login.
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
     * Check user exists by Id.
     *
	 * @access public
	 * @param mixed $id
	 * @return bool
	 */
	public static function exists($id) : bool
	{
		$user = self::get($id, false);
		return ($user) ? $user->exists() : false;
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
		if ( TypeCheck::isNull($id) ) {
			$id = self::getId();
		}
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
	public static function addMeta(string $key, $value, $id = null, bool $unique = true)
	{
		if ( TypeCheck::isNull($id) ) {
			$id = self::getId();
		}
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
		if ( TypeCheck::isNull($id) ) {
			$id = self::getId();
		}
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
		if ( TypeCheck::isNull($id) ) {
			$id = self::getId();
		}
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
		if ( TypeCheck::isNull($id) ) {
			$id = self::getId();
		}
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
     * Advanced user login.
     *
	 * @access public
	 * @param string $user
	 * @param string $pswd
	 * @param bool $memory
	 * @return bool
	 */
	public static function login(string $user, string $pswd, bool $memory = false) : bool
	{
		$args = Format::undash([
			'user-login'    => $user,
			'user-password' => $pswd,
			'remember'      => $memory
		]);
		$login = wp_signon($args, Server::isSsl());

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
		if ( TypeCheck::isNull($id) ) {
			$id = self::getId();
		}
		return Password::isValid($pswd, $hash, $id);
	}

	/**
     * Update password.
     *
	 * @access public
	 * @param string $pswd
	 * @param mixed $id
	 * @return bool
	 */
	public static function updatePassword(string $pswd, $id = null) : bool
	{
		if ( TypeCheck::isNull($id) ) {
			$id = self::getId();
		}
		return (bool)wp_set_password($pswd, (int)$id);
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
        if ( ($user = self::getById($id)) ) {
           return Password::send($user['login']);
        }
		return false;
	}

	/**
     * Get reset key.
     *
	 * @access public
	 * @param object $user
	 * @return mixed
	 */
	public static function getResetKey(object $user)
	{
		$key = get_password_reset_key($user);
		if ( !Exception::isError($key) ) {
			return $key;
		}
		return false;
	}

	/**
	 * Get password reset URL.
	 *
	 * @access public
	 * @param object $user
	 * @return string
	 */
	public static function getResetUrl(object $user) : string
	{
		$url = wp_lostpassword_url();
		$key = self::getResetKey($user);
		$login = rawurlencode($user->user_login);
		return "{$url}?action=rp&key={$key}&login={$login}";
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
		$user = self::get($id, false);
		return ($user) ? (array)$user->roles : [];
	}

	/**
	 * Check whether user has role.
	 *
	 * @access public
	 * @param string $role
	 * @param mixed $id
	 * @return bool
	 */
	public static function hasRole(string $role, $id = null) : bool
	{
		$roles = self::getRoles($id);
		return Arrayify::inArray($role, $roles);
	}

	/**
	 * Get role object.
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
	 * Add role.
	 *
	 * @access public
	 * @param string $display
	 * @param string $role
	 * @param array $cap
	 * @return mixed
	 */
	public static function addRole(string $display, ?string $role = null, array $cap = [])
	{
		$role = ($role) ? (string)$role : $display;
		$role = Stringify::undash(
			Stringify::slugify($role)
		);
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
	 * Get user capabilities.
	 *
	 * @access public
	 * @param mixed $id
	 * @return array
	 */
	public static function getCaps($id = null) : array
	{
		$user = self::get($id, false);
		return ($user) ? (array)$user->caps : [];
	}

	/**
	 * Check user capability.
	 *
	 * @access public
	 * @param string $cap
	 * @param mixed $id
	 * @param array $args
	 * @return bool
	 */
	public static function hasCapability(string $cap = 'edit-posts', $id = null, ...$args) : bool
	{
		$cap = Stringify::undash($cap);
		if ( TypeCheck::isNull($id) ) {
			return current_user_can($cap, ...$args);
		}
		return user_can($id, $cap, ...$args);
	}

	/**
	 * Check user capability (Alias).
	 *
	 * @access public
	 * @param string $cap
	 * @param mixed $id
	 * @param array $args
	 * @return bool
	 */
	public static function hasCap(string $cap = 'edit-posts', $id = null, ...$args) : bool
	{
		return self::hasCapability($cap, $id, ...$args);
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
	 * Add role capability (Alias).
	 *
	 * @access public
	 * @param string $role
	 * @param string $cap
	 * @param bool $grant
	 * @return bool
	 */
	public static function addCap(string $role, string $cap, bool $grant = true) : bool
	{
		return self::addCapability($role, $cap, $grant);
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
	 * Remove role capability (Alias).
	 *
	 * @access public
	 * @param string $role
	 * @param string $cap
	 * @return bool
	 */
	public static function removeCap(string $role, string $cap) : bool
	{
		return self::removeCapability($role, $cap);
	}
}
