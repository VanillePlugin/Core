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

namespace VanillePlugin\tr;

use VanillePlugin\inc\User;

/**
 * Define authentication functions.
 */
trait TraitAuthenticatable
{
	use TraitSessionable,
		TraitPermissionable,
		TraitSecurable;

	/**
	 * Check whether user exists.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isUser($user, string $property = 'username') : bool
	{
		return User::isUser($user, $property);
	}

	/**
	 * Check whether user is logged-in.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isLoggedIn() : bool
	{
		return User::isLoggedIn();
	}

	/**
	 * Validate user password.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isPassword(string $pswd, string $hash, $id = null) : bool
	{
		return User::isPassword($pswd, $hash, $id);
	}

	/**
	 * Get user by Id.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getUser($id = null)
	{
		return User::get($id);
	}

	/**
	 * Get current user Id.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getUserId() : int
	{
		return User::getId();
	}

	/**
     * Get user by field.
     *
	 * @access public
	 * @inheritdoc
	 */
	public function getUserBy(string $key, $value)
	{
		return User::getBy($key, $value);
	}

	/**
     * Get user by Id.
     *
	 * @access public
	 * @inheritdoc
	 */
	public function getUserById($id = null)
	{
		return User::getById($id);
	}

	/**
     * Get user by login.
     *
	 * @access public
	 * @inheritdoc
	 */
	public function getUserByLogin(string $login)
	{
		return User::getByLogin($login);
	}

	/**
     * Get user by email.
     *
	 * @access public
	 * @inheritdoc
	 */
	public function getUserByEmail(string $email)
	{
		return User::getByEmail($email);
	}

	/**
     * Get users by meta.
     *
	 * @access public
	 * @inheritdoc
	 */
	public function getUserByMeta(string $key, $value) : array
	{
		return User::getByMeta($key, $value);
	}

	/**
     * Get user meta.
     *
	 * @access public
	 * @inheritdoc
	 */
	public function getUserMeta(string $key, $id = null)
	{
		return User::getMeta($key, $id);
	}

	/**
	 * Register user.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function registerUser(string $email, ?string $pswd = null, ?string $user = null)
	{
		return User::register($email, $pswd, $user);
	}

	/**
	 * Advanced user login.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function login(string $user, string $pswd, bool $memory = false) : bool
	{
		return User::login($user, $pswd, $memory);
	}

	/**
	 * Authenticate user.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function authenticate(string $user, string $pswd)
	{
		return User::authenticate($user, $pswd);
	}

	/**
	 * Logout user.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function logout()
	{
		User::logout();
	}

	/**
	 * Send user password.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function sendPassword($id = null) : bool
	{
		return User::sendPassword($id);
	}

	/**
     * Add user meta.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addUserMeta(string $key, $value, $id = null)
	{
		return User::addMeta($key, $value, $id);
	}

	/**
     * Update user meta.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function updateUserMeta(string $key, $value, $id = null)
	{
		return User::updateMeta($key, $value, $id);
	}

	/**
     * Delete user meta.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function deleteUserMeta(string $key, $id = null, $value = null) : bool
	{
		return User::deleteMeta($key, $id, $value);
	}
}
