<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\User;

/**
 * Define permissions functions.
 */
trait TraitPermissionable
{
	/**
	 * Get user roles.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getRoles($id = null): array
	{
		return User::getRoles($id);
	}

	/**
	 * Get role object.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getRole(string $role)
	{
		return User::getRole($role);
	}

	/**
	 * Check whether user has role.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function hasRole(string $role, $id = null): bool
	{
		return User::hasRole($role, $id);
	}

	/**
	 * Check whether user is administrator.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isAdministrator($id = null): bool
	{
		return $this->hasRole('administrator', $id);
	}

	/**
	 * Get user capabilities.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getCaps($id = null): array
	{
		return User::getCaps($id);
	}

	/**
	 * Check user capability.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function hasCap(string $cap = 'edit-posts', $id = null, ...$args): bool
	{
		return User::hasCap($cap, $id, ...$args);
	}

	/**
	 * Add role.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addRole(string $display, string $role = null, array $cap = [])
	{
		return User::addRole($role, $display, $cap);
	}

	/**
	 * Remove role.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeRole(string $role)
	{
		User::removeRole($role);
	}

	/**
	 * Add role capability.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addCap(string $role, string $cap, bool $grant = true): bool
	{
		return User::addCap($role, $cap, $grant);
	}

	/**
	 * Remove role capability.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeCap(string $role, string $cap): bool
	{
		return User::removeCap($role, $cap);
	}
}
