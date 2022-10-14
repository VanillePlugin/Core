<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.8.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

namespace VanillePlugin\inc;

use VanillePlugin\lib\PluginOptions;
use VanillePlugin\int\LoggerInterface;

/**
 * API Request Client Helper.
 */
class API extends Request
{
	/**
	 * @access protected
	 * @var object $logger, API logger
	 */
	protected $logger;

	/**
	 * @param string $method
	 * @param array $args
	 * @param string $baseUrl
	 */
	public function __construct($method = 'GET', $args = [], $baseUrl = null)
	{
		$this->method = $method;
		$this->baseUrl = $baseUrl;
		$this->args = Arrayify::merge([
			'timeout'     => 30,
			'redirection' => 0,
			'sslverify'   => Server::maybeRequireSSL()
		], $args);
	}

	/**
	 * Set API authentication.
	 * 
	 * @access public
	 * @param string $token
	 * @return void
	 */
	public function setAuthentication($token)
	{
		$this->addHeader(
			'Authorization',
			sprintf('Bearer %s',$token)
		);
	}

	/**
	 * Set API basic authentication.
	 * 
	 * @access public
	 * @param string $user
	 * @param string $pswd
	 * @return void
	 */
	public function setBasicAuthentication($user = '', $pswd = '')
	{
		$token = Tokenizer::base64("{$user}:{$pswd}");
		$this->addHeader(
			'Authorization',
			sprintf('Basic %s',$token)
		);
	}

	/**
	 * Get API formated response from body (JSON),
	 * Returns false if request failed,
	 * Logs error if logger is setted.
	 * 
	 * @access public
	 * @param bool $isArray
	 * @return mixed
	 */
	public function getResponse($isArray = true)
	{
		if ( !$this->hasError() ) {
			return Json::decode($this->getBody(),$isArray);
		}
		if ( $this->logger ) {
			if ( $this->logger->isDebug() ) {
				$this->logger->debug($this->response,true);
			}
		}
		return false;
	}

	/**
	 * Get API formated response from body (XML),
	 * Returns false if request failed,
	 * Logs error if logger is setted.
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public function getXmlResponse()
	{
		if ( !$this->hasError() ) {
			return ResponseXML::parse($this->getBody());
		}
		if ( $this->logger ) {
			if ( $this->logger->isDebug() ) {
				$this->logger->debug($this->response,true);
			}
		}
		return false;
	}

	/**
	 * Check if API response has status.
	 * 
	 * @access public
	 * @param string $status
	 * @return bool
	 */
	public function hasStatus($status)
	{
		if ( !$this->hasError() ) {
			$body = Json::decode($this->getBody(),true);
			if ( isset($body['status']) && $body['status'] == $status ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if API response has content.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public function hasContent()
	{
		if ( !$this->hasError() ) {
			$body = Json::decode($this->getBody(),true);
			return (isset($body['content']) && !empty($body['content']));
		}
		return false;
	}

	/**
	 * Check API server response code,
	 * Down when response code lower than expected code (Default 429),
	 * or when response code equals 0.
	 * 
	 * @access public
	 * @param int $code
	 * @return bool
	 */
	public function isDown($code = 429)
	{
		if ( !$this->getStatusCode() ) {
			return true;
		} elseif ( $this->getStatusCode() >= intval($code) ) {
			return true;
		}
		return false;
	}

	/**
	 * Set API logger.
	 * 
	 * @access protected
	 * @param object $logger
	 * @return void
	 * @todo logger
	 */
	protected function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
}
