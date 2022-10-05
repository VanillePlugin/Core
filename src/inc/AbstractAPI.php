<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.9
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

namespace VanillePlugin\inc;

use VanillePlugin\lib\PluginOptions;

/**
 * API Client Helper.
 */
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
	protected $client = null;
	protected $response = null;

	/**
	 * @access public
	 * @param array $params
	 * @return void
	 */
	abstract public function setClient($params = null);

	/**
	 * Get current server response.
	 * 
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
	 * Get current server status code.
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public function getStatusCode()
	{
		if ( $this->response ) {
			return $this->response->getStatusCode();
		}
		$this->error = true;
		return false;
	}

	/**
	 * Check current server status code.
	 * 
	 * @access public
	 * @param int $code
	 * @return bool
	 */
	public function isDown($code = 429)
	{
		if ( $this->getStatusCode() >= intval($code) ) {
			return true;
		}
		return false;
	}
}
