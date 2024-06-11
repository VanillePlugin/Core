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

use VanillePlugin\inc\Transient;
use VanillePlugin\lib\Orm;

trait TraitCacheable
{
	/**
	 * Get transient.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getTransient(string $key)
	{
		return Transient::get($key);
	}

	/**
	 * Get site transient.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getSiteTransient(string $key)
	{
		return Transient::getSite($key);
	}

	/**
	 * Set transient.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function setTransient(string $key, $value = 1, int $ttl = 300) : bool
	{
		return Transient::set($key, $value, $ttl);
	}

	/**
	 * Set site transient.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function setSiteTransient(string $key, $value = 1, int $ttl = 300) : bool
	{
		return Transient::setSite($key, $value, $ttl);
	}

	/**
	 * Delete transient.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function deleteTransient(string $key) : bool
	{
		return Transient::delete($key);
	}

	/**
	 * Delete site transient.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function deleteSiteTransient(string $key) : bool
	{
		return Transient::deleteSite($key);
	}

	/**
	 * Purge transients,
	 * Under plugin namespace.
	 *
	 * @access protected
	 * @param string $namespace
	 * @return bool
	 */
	protected function purgeTransients(string $namespace) : bool
	{
		if ( empty($namespace) ) {
			return false;
		}
		$db = new Orm();
		$sql = "DELETE FROM {$db->prefix}options WHERE `option_name` LIKE '%_transient_{$namespace}%';";
		return (bool)$db->execute($sql);
	}

	/**
	 * Purge site transients,
	 * Under plugin namespace.
	 *
	 * @access protected
	 * @param string $namespace
	 * @return bool
	 */
	protected function purgeSiteTransients(string $namespace) : bool
	{
		if ( empty($namespace) ) {
			return false;
		}
		$db = new Orm();
		$sql = "DELETE FROM {$db->prefix}options WHERE `option_name` LIKE '%_site_transient_{$namespace}%';";
		return (bool)$db->execute($sql);
	}
}
