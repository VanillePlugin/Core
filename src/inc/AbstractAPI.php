<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.4
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use VanillePlugin\lib\PluginOptions;
use VanillePlugin\lib\Request;

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
	 * @param bool $isArray
	 * @return mixed
	 */
	public function getResponse($isArray = false)
	{
		if ( $this->response ) {
			return Response::get($this->response->getBody(),$isArray);
		}
		$this->error = true;
		return false;
	}

	/**
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public function getStatus()
	{
		if ( $this->response ) {
			return $this->response->getStatusCode();
		}
		$this->error = true;
		return false;
	}

	/**
	 * @access public
	 * @param string $host
	 * @return bool
	 */
	public function isDown($host = null)
	{
		if ( $host ) {
			$request = new Request();
			$request->get($host);
			if ( $request->getStatusCode() >= 500 ) {
				return true;
			}
		} else {
			if ( $this->getStatus() >= 500 ) {
				return true;
				
			} elseif ( empty($this->getResponse()) ) {
				return true;
			}
		}
		return false;
	}
}
