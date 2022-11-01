<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

use VanillePlugin\int\LoggerInterface;

/**
 * Advanced API Request Client Helper.
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
			'sslverify'   => true
		], Server::maybeRequireSSL($args));
	}

	/**
	 * Send request,
	 * Logs errors if logger is setted.
	 *
	 * @access public
	 * @param string $url
	 * @return object
	 */
	public function send($url = null)
	{
		parent::send($url);
		if ( $this->hasError() && $this->logger ) {
			if ( $this->logger->isDebug() ) {
				$this->logger->debug("Request response:");
				$this->logger->debug($this->response,true);
				$this->logger->debug("Request args:");
				$this->logger->debug($this->args,true);
				$this->logger->debug("Request body:");
				$this->logger->debug($this->body,true);
			}
		}
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
	 * Returns false if request failed.
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
		return false;
	}

	/**
	 * Get API formated response from body (XML),
	 * Returns false if request failed.
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
	 * Force disabling SSL verification.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function forceDisableSSL()
	{
		$this->args['sslverify'] = false;
	}

	/**
	 * Set API logger.
	 * 
	 * @access protected
	 * @param object $logger
	 * @return void
	 */
	protected function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
}
