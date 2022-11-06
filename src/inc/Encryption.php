<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.1
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

/**
 * Built-in Encryption Class,
 * @see Using JWT instead is recommended.
 */
class Encryption
{
	/**
	 * @access private
	 * @var SECRET default passphrase
	 * @var VECTOR default initialzation vector
	 */
	const SECRET = 'v8t1pQ92PN';
	const VECTOR = 'ZRfvSPsFQ';

	/**
	 * @access private
	 * @var string $data
	 * @var string $key, Secret key (Passphrase)
	 * @var string $vector, Initialzation vector
	 * @var int $length, Encryption length
	 * @var string $prefix, Encryption prefix
	 * @var int $options, Openssl options
	 * @var string $algo, Hash algorithm
	 * @var string $cipher, Openssl cipher algorithm
	 */
	private $data;
	private $key;
	private $vector;
	private $length;
	private $prefix = '[vanillecrypt]';
	private $options = 0;
	private $algo = 'sha256';
	private $cipher = 'AES-256-CBC';

	/**
	 * @param string $data
	 * @param string $vector
	 * @param string $key
	 */
	public function __construct($data, $key = self::SECRET, $vector = self::VECTOR, $length = 16)
	{
		$this->data = $data;
		$this->setSecretKey($key);
		$this->setInitVector($vector);
		$this->setLength($length);
		$this->initialize();
	}

	/**
	 * Set secret key (Passphrase).
	 * 
	 * @access public
	 * @param string $key
	 * @param void
	 */
	public function setSecretKey($key)
	{
		$this->key = $key;
	}

	/**
	 * Set initialzation vector.
	 * 
	 * @access public
	 * @param string $vector
	 * @param void
	 */
	public function setInitVector($vector)
	{
		$this->vector = $vector;
	}

	/**
	 * Set encryption length.
	 * 
	 * @access public
	 * @param int $length
	 * @param void
	 */
	public function setLength($length)
	{
		$this->length = $length;
	}

	/**
	 * Set openssl cipher algorithm.
	 * 
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
	 * Set openssl options.
	 * 
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
	 * Set encryption prefix.
	 * 
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
	 * Encrypt data using base64 loop.
	 * 
	 * @access public
	 * @param int $loop, base64 loop
	 * @param string
	 */
	public function encrypt($loop = 1)
	{
		$encrypt = openssl_encrypt($this->data,$this->cipher,$this->key,$this->options,$this->vector);
		$crypted = Tokenizer::base64($encrypt,$loop);
		return "{$this->prefix}{$crypted}";
	}

	/**
	 * Decrypt data using base64 loop.
	 * 
	 * @access public
	 * @param int $loop, base64 loop
	 * @param string
	 */
	public function decrypt($loop = 1)
	{
		$decrypted = Stringify::replace($this->prefix,'',$this->data);
		return openssl_decrypt(
			Tokenizer::unbase64($decrypted,$loop),$this->cipher,$this->key,$this->options,$this->vector
		);
	}

	/**
	 * Check data is crypted using prefix.
	 * 
	 * @access public
	 * @param void
	 * @param bool
	 */
	public function isCrypted()
	{
		return substr($this->data,0,strlen($this->prefix)) === $this->prefix;
	}

	/**
	 * Initialize hash.
	 * 
	 * @access protected
	 * @param void
	 * @param object
	 */
	protected function initialize()
	{
		$this->key = hash($this->algo,$this->key);
		$this->vector = substr(hash($this->algo,$this->vector),0,$this->length);
		return $this;
	}
}
