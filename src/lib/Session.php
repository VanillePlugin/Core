<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.2
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\NameSpaceInterface;
use VanillePlugin\int\PluginNameSpaceInterface;

class Session extends PluginOptions implements NameSpaceInterface
{
	/**
	 * @param PluginNameSpaceInterface $plugin
	 * @return void
	 */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);
        if ( !$this->isSetted() ) {
            session_start();
        }
	}
    
    /**
     * Register the session
     *
     * @access public
     * @param int $time 60
     * @return void
     */
    public function register($time = 60)
    {
        $this->set('session_id', session_id());
        $this->set('session_time', intval($time));
        $this->set('session_start', $this->newTime());
    }

    /**
     * Check if session is registered
     *
     * @access public
     * @param void
     * @return boolean
     */
    public function isRegistered()
    {
        if ( !empty($this->get('session_id')) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set key in session
     *
     * @access public
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        $_SESSION[$this->getNameSpace()][$key] = $value;
    }

    /**
     * Retrieve value stored in session by key
     *
     * @access public
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->isSetted($key) 
        ? $_SESSION[$this->getNameSpace()][$key] : false;
    }

    /**
     * Check key exists
     *
     * @access public
     * @param string $key null
     * @return boolean
     */
    public function isSetted($key = null)
    {
        if ($key) {
            return isset($_SESSION[$this->getNameSpace()][$key]);
        } else {
            return isset($_SESSION);
        }
    }

    /**
     * Retrieve global session variable
     *
     * @access public
     * @param void
     * @return array
     */
    public function getSession()
    {
        return $_SESSION;
    }

    /**
     * Get id for current session
     *
     * @access public
     * @param void
     * @return int
     */
    public function getSessionId()
    {
        return $this->get('session_id');
    }

    /**
     * Check if session is over
     *
     * @access public
     * @param void
     * @return boolean
     */
    public function isExpired()
    {
        if ( $this->get('session_start') < $this->timeNow() ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Renew session when the given time is not up
     *
     * @access public
     * @param void
     * @return void
     */
    public function renew()
    {
        $this->set('session_start', $this->newTime());
    }

    /**
     * Return current time
     *
     * @access public
     * @param void
     * @return unix timestamp
     */
    private function timeNow()
    {
        $currentHour = date('H');
        $currentMin  = date('i');
        $currentSec  = date('s');
        $currentMon  = date('m');
        $currentDay  = date('d');
        $currentYear = date('y');
        return mktime(
            $currentHour,
            $currentMin,
            $currentSec,
            $currentMon,
            $currentDay,
            $currentYear
        );
    }

    /**
     * Generates new time
     *
     * @access public
     * @param void
     * @return unix timestamp
     */
    private function newTime()
    {
        $currentHour = date('H');
        $currentMin  = date('i');
        $currentSec  = date('s');
        $currentMon  = date('m');
        $currentDay  = date('d');
        $currentYear = date('y');
        return mktime(
            $currentHour,
            ($currentMin + $this->get('session_time')),
            $currentSec,
            $currentMon,
            $currentDay,
            $currentYear
        );
    }

    /**
     * Destroy session
     *
     * @access public
     * @param void
     * @return void
     */
    public function end()
    {
        session_destroy();
        $_SESSION = [];
    }
}
