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

trait TraitSessionable
{
	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function startSession()
    {
        new Session();
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function setSession($key, $value = null)
    {
        Session::set($key, $value);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function getSession($key = null)
    {
        return Session::get($key);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function hasSession($key = null) : bool
    {
        return Session::isSetted($key);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function registerSession($time = 60)
    {
        Session::register($time);
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function isSessionRegistered() : bool
	{
		return Session::isRegistered();
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function isSessionExpired() : bool
	{
		return Session::isExpired();
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function closeSession() : bool
    {
        return Session::close();
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function endSession() : bool
    {
        return Session::end();
    }

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function getCookie(?string $key = null)
	{
        return Cookie::get($key);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function setCookie(string $key, $value = '', $options = [])
	{
		return Cookie::set($key, $value, $options);
	}
	
	/**
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasCookie(?string $key = null)
	{
        return Cookie::isSetted($key);
	}

	/**
	 * @access protected
	 * @inheritdoc
	 */
    protected function clearCookie() : bool
    {
        return Cookie::clear();
    }
}
