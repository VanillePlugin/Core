<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.4
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use VanillePlugin\lib\PluginOptions;

abstract class AbstractAPI extends PluginOptions
{
	/**
	 * @access public
	 * @var boolean $error
	 */
	public $error = false;

	/**
	 * @access protected
	 * @var object $client
	 * @var object $response
	 */
	protected $client;
	protected $response;

	/**
	 * @access public
	 * @param array $params
	 * @return void
	 */
	abstract public function setClient($params = null);

	/**
	 * @access public
	 * @param bool $array
	 * @return string
	 */
	public function getResponse($array = false)
	{
		return Response::get($this->response->getBody(), $array);
	}

	/**
	 * @access public
	 * @param void
	 * @return int
	 */
	public function getStatus()
	{
		return $this->response->getStatusCode();
	}

	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public function isDown()
	{
		if ( $this->getStatus() > 401 ) {
			return true;
		} elseif ( empty($this->getResponse()) ) {
			return true;
		}
		return false;
	}
}
