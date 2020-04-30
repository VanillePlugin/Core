<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
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
	 * @param void
	 * @return void
	 */
	abstract public function setClient($params = null);

	/**
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getResponse()
	{
		return Response::get( $this->response->getBody(), false);
	}

	/**
	 * @access protected
	 * @param void
	 * @return int
	 */
	protected function getStatus()
	{
		return $this->response->getStatusCode();
	}

	/**
	 * @access protected
	 * @param void
	 * @return boolean
	 */
	protected function isDown()
	{
		if ( $this->getStatus() > 401 ) {
			return true;
		} elseif ( empty($this->getResponse()) ) {
			return true;
		}
		return false;
	}
}
