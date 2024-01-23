<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\User;

trait TraitPermissionable
{
	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getRoles($id = null) : array
	{
		return User::getRoles($id);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getCaps($id = null) : array
	{
		return User::getCaps($id);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getRole(string $role)
	{
		return User::getRole($role);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function addRole(string $display, string $role = null, array $cap = [])
	{
		return User::addRole($role, $display, $cap);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeRole(string $role)
	{
		User::removeRole($role);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function addCapability(string $role, string $cap, bool $grant = true) : bool
	{
		return User::addCapability($role, $cap, $grant);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeCapability(string $role, string $cap) : bool
	{
		return User::removeCapability($role, $cap);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasCapability(string $cap = 'edit_posts', $args = null) : bool
	{
		return User::hasCapability($cap, $args);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasPermission(string $cap = 'edit_posts', $args = null, $id = null) : bool
	{
		return User::hasPermission($cap, $args, $id);
	}
}
