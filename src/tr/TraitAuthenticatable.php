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

namespace VanillePlugin\tr;

use VanillePlugin\inc\User;

trait TraitAuthenticatable
{
	use TraitSessionable,
		TraitPermissionable,
		TraitSecurable;

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function registerUser(string $email, ?string $pswd = null, ?string $user = null)
	{
		return User::register($email, $pswd, $user);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function login(string $user, string $pswd, bool $memory = false) : bool
	{
		return User::login($user, $pswd, $memory);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function authenticate(string $user, string $pswd)
	{
		return User::authenticate($user, $pswd);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function logout()
	{
		User::logout();
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function isUser($user, string $property = 'username') : bool
	{
		return User::isUser($user, $property);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function isLoggedIn() : bool
	{
		return User::isLoggedIn();
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function isPassword(string $pswd, string $hash, $id = null) : bool
	{
		return User::isPassword($pswd, $hash, $id);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function sendPassword($id = null) : bool
	{
		return User::sendPassword($id);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getUser($id = null, bool $format = true)
	{
		return User::get($id, $format);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getUserId() : int
	{
		return User::getId();
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
	protected function addUserMeta(string $key, $value, $id = null, bool $unique = true)
	{
		return User::addMeta($key, $value, $id, $unique);
	}

	/**
     * Get user meta.
     * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function getUserMeta(string $key, $id = null, bool $single = true)
	{
		return User::getMeta($key, $id, $single);
	}

	/**
     * Update user meta.
     * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function updateUserMeta(string $key, $value, $id = null)
	{
		return User::getMeta($key, $value, $id);
	}

	/**
     * Delete user meta.
     * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function deleteUserMeta(string $key, $id = null, $value = null) : bool
	{
		return User::getMeta($key, $id, $value);
	}
}
