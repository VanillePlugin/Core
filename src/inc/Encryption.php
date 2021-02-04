<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.6
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Encryption
{
	/**
	 * @access public
	 */
	const VECTOR = 'ZRfvSPsFQ';
	const KEY = 'v8t1pQ92PN';

	/**
	 * @access private
	 * @var string $initVector | Initialization Vector
	 * @var string $secretKey
	 * @var string $password
	 */
	private $initVector;
	private $secretKey;
	private $password;
	private $length;
	private $prefix = '[vanillecrypt]';
	private $method = 'AES-256-CBC';

	/**
	 * @param string $password
	 * @param string $initVector
	 * @param string $secretKey
	 */
	public function __construct($password, $initVector = self::VECTOR, $secretKey = self::KEY, $length = 16)
	{
		$this->password = $password;
		$this->initVector = $initVector;
		$this->secretKey = $secretKey;
		$this->length = $length;
		$this->initialize();
	}

	/**
	 * @access private
	 * @param void
	 * @param void
	 */
	private function initialize()
	{
		$this->secretKey = hash('sha256',$this->secretKey);
		$this->initVector = substr(hash('sha256',$this->initVector),0,$this->length);
	}

	/**
	 * @access public
	 * @param string $method
	 * @param object
	 */
	public function setMethod($method)
	{
		$this->method = $method;
		return $this;
	}

	/**
	 * @access public
	 * @param string $prefix
	 * @param object
	 */
	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
		return $this;
	}

	/**
	 * @access public
	 * @param void
	 * @param string | Hashed
	 */
	public function encrypt()
	{
		$crypted = base64_encode(
			openssl_encrypt($this->password, $this->method, $this->secretKey, 0, $this->initVector)
		);
		return "{$this->prefix}{$crypted}";
	}

	/**
	 * @access public
	 * @param void
	 * @param string | Hashed
	 */
	public function decrypt()
	{
		$decrypted = Stringify::replace($this->prefix, '', $this->password);
		return openssl_decrypt(
			base64_decode($decrypted), $this->method, $this->secretKey, 0, $this->initVector
		);
	}

	/**
	 * @access public
	 * @param void
	 * @param boolean
	 */
	public function isCrypted()
	{
		return substr($this->password, 0, strlen($this->prefix)) === $this->prefix;
	}
}
