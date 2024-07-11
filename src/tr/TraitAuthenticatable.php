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

trait TraitAuthenticatable
{
	use TraitSessionable,
		TraitPermissionable,
		TraitSecurable;

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
	 * Check whether user exists.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isUser($user, string $property = 'username') : bool
	{
		return User::isUser($user, $property);
	}

	/**
	 * Check whether user is logged-in.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isLoggedIn() : bool
	{
		return User::isLoggedIn();
	}

	/**
	 * Validate user password.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isPassword(string $pswd, string $hash, $id = null) : bool
	{
		return User::isPassword($pswd, $hash, $id);
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
	 * Get user by Id.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getUser($id = null)
	{
		return User::get($id);
	}

	/**
	 * Get current user Id.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getUserId() : int
	{
		return User::getId();
	}

	/**
     * Get user by field.
     * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function getUserBy(string $key, $value)
	{
		return User::getBy($key, $value);
	}

	/**
     * Get users by meta.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getUserByMeta(string $key, $value) : array
	{
		return User::getByMeta($key, $value);
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
     * Get user meta.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getUserMeta(string $key, $id = null)
	{
		return User::getMeta($key, $id);
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
