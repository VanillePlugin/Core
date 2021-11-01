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

class Encryption
{
	/**
	 * @access private
	 */
	const SECRET = 'v8t1pQ92PN';
	const VECTOR = 'ZRfvSPsFQ';

	/**
	 * @access private
	 * @var string $data
	 * @var string $initVector
	 * @var string $secretKey
	 * @var int $length
	 * @var int $options
	 * @var string $algorithm
	 * @var string $cipher
	 */
	private $data;
	private $initVector;
	private $secretKey;
	private $length;
	private $prefix = '[vanillecrypt]';
	private $options = 0;
	private $algorithm = 'sha256';
	private $cipher = 'AES-256-CBC';

	/**
	 * @param string $data
	 * @param string $initVector
	 * @param string $secretKey
	 */
	public function __construct($data, $secretKey = self::SECRET, $initVector = self::VECTOR, $length = 16)
	{
		$this->data = $data;
		$this->setSecretKey($secretKey);
		$this->setInitVector($initVector);
		$this->setLength($length);
		$this->initialize();
	}

	/**
	 * @access public
	 * @param string $key
	 * @param void
	 */
	public function setSecretKey($key)
	{
		$this->secretKey = $key;
	}

	/**
	 * @access public
	 * @param string $vector
	 * @param void
	 */
	public function setInitVector($vector)
	{
		$this->initVector = $vector;
	}

	/**
	 * @access public
	 * @param int $length
	 * @param void
	 */
	public function setLength($length)
	{
		$this->length = $length;
	}

	/**
	 * @access public
	 * @param string $cipher
	 * @param object
	 */
	public function setCipher($cipher)
	{
		$this->cipher = $cipher;
		return $this;
	}

	/**
	 * @access public
	 * @param int $options
	 * @param object
	 */
	public function setOptions($options = OPENSSL_ZERO_PADDING)
	{
		$this->options = $options;
		return $this;
	}

	/**
	 * @access public
	 * @param void
	 * @param object
	 */
	public function initialize()
	{
		$this->secretKey = hash($this->algorithm,$this->secretKey);
		$this->initVector = substr(hash($this->algorithm,$this->initVector),0,$this->length);
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
	 * @param int $loop
	 * @param string
	 */
	public function encrypt($loop = 1)
	{
		$encrypt = openssl_encrypt($this->data,$this->cipher,$this->secretKey,$this->options,$this->initVector);
		$crypted = Tokenizer::base64($encrypt,$loop);
		return "{$this->prefix}{$crypted}";
	}

	/**
	 * @access public
	 * @param int $loop
	 * @param string
	 */
	public function decrypt($loop = 1)
	{
		$decrypted = Stringify::replace($this->prefix,'',$this->data);
		return openssl_decrypt(
			Tokenizer::unbase64($decrypted,$loop),$this->cipher,$this->secretKey,$this->options,$this->initVector
		);
	}

	/**
	 * @access public
	 * @param void
	 * @param bool
	 */
	public function isCrypted()
	{
		return substr($this->data,0,strlen($this->prefix)) === $this->prefix;
	}
}
