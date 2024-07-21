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

use VanillePlugin\inc\{
	Session, Cookie
};

/**
 * Define session functions.
 */
trait TraitSessionable
{
	/**
	 * Get session value.
	 *
	 * @access public
	 * @inheritdoc
	 */
    public function getSession(?string $key = null)
    {
        return Session::get($key);
    }

	/**
	 * Check session value.
	 *
	 * @access public
	 * @inheritdoc
	 */
    public function hasSession(?string $key = null) : bool
    {
        return Session::isSetted($key);
    }

	/**
	 * Check whether session is registered.
	 *
	 * @access public
	 * @inheritdoc
	 */
    public function isSessionRegistered() : bool
	{
		return Session::isRegistered();
	}

	/**
	 * Check whether session is expired.
	 *
	 * @access public
	 * @inheritdoc
	 */
    public function isSessionExpired() : bool
	{
		return Session::isExpired();
	}

	/**
	 * Get cookie value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getCookie(?string $key = null)
	{
        return Cookie::get($key);
	}
	
	/**
	 * Check cookie value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function hasCookie(?string $key = null)
	{
        return Cookie::isSetted($key);
	}

	/**
	 * Start session if not active.
	 *
	 * @access protected
	 * @inheritdoc
	 */
    protected function startSession()
    {
        new Session();
    }

	/**
	 * Set session value.
	 *
	 * @access protected
	 * @inheritdoc
	 */
    protected function setSession($key, $value = null)
    {
        Session::set($key, $value);
    }

	/**
	 * Register session.
	 *
	 * @access protected
	 * @inheritdoc
	 */
    protected function registerSession($time = 60)
    {
        Session::register($time);
    }

	/**
	 * Close session (Read-only).
	 *
	 * @access protected
	 * @inheritdoc
	 */
    protected function closeSession() : bool
    {
        return Session::close();
    }

	/**
	 * End session (Destroy).
	 *
	 * @access protected
	 * @inheritdoc
	 */
    protected function endSession() : bool
    {
        return Session::end();
    }

	/**
	 * Set cookie value.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function setCookie(string $key, $value = '', $options = [])
	{
		return Cookie::set($key, $value, $options);
	}

	/**
	 * Clear session cookie.
	 *
	 * @access protected
	 * @inheritdoc
	 */
    protected function clearCookie() : bool
    {
        return Cookie::clear();
    }
}
