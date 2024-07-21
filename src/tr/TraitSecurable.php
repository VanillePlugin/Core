<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\{
    Encryption, Tokenizer, Arrayify
};

/**
 * Define security functions.
 */
trait TraitSecurable
{
	/**
	 * Check nonce.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function checkNonce(string $nonce, $action = -1) : bool
	{
		return Tokenizer::checkNonce($nonce, $action);
	}

	/**
	 * Check Ajax nonce.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function checkAjaxNonce($nonce, $action = -1) : bool
	{
		return Tokenizer::checkAjaxNonce($action, $nonce);
	}

    /**
     * Encode base64.
     *
	 * @access public
	 * @inheritdoc
     */
    public function base64(string $value, int $loop = 1) : string
	{
		return Tokenizer::base64($value, $loop);
	}

    /**
     * Decode base64.
     *
	 * @access public
	 * @inheritdoc
     */
    public function unbase64(string $value, int $loop = 1) : string
	{
		return Tokenizer::unbase64($value, $loop);
	}

    /**
     * Get unique Id.
     *
	 * @access public
	 * @inheritdoc
     */
    public function getUniqueId() : string
	{
		return Tokenizer::getUniqueId();
	}

    /**
     * Generate token.
     *
	 * @access public
	 * @inheritdoc
     */
    public function generateToken(int $length = 16, bool $special = false) : string
	{
		return Tokenizer::generate($length, $special);
	}

    /**
     * Generate hash.
     *
	 * @access public
	 * @inheritdoc
     */
    public function generateHash($data, string $salt = 'Y3biC') : string
	{
		return Tokenizer::hash($data, $salt);
	}
    /**
     * Get access token.
     *
	 * @access public
	 * @inheritdoc
     */
    public function getAccessToken($data, string $secret, ?string $prefix = null) : string
	{
		$data = Arrayify::merge([
			'user' => false,
			'pswd' => false
		], $data);
		return $this->encrypt($data, $secret, $prefix);
	}

    /**
     * Get access from token.
     *
	 * @access public
	 * @inheritdoc
     */
    public function getAccess(string $token, string $secret, ?string $prefix = null) : array
	{
		$access = (array)$this->decrypt($token, $secret, $prefix);
		return Arrayify::merge([
			'user' => false,
			'pswd' => false
		], $access);
	}

    /**
     * Encrypt data.
     *
	 * @access protected
	 * @inheritdoc
     */
    protected function encrypt($data, ?string $secret = null, ?string $prefix = null) : string
    {
		$cryptor = new Encryption($data, $secret);
        return $cryptor->setPrefix($prefix)->encrypt();
    }

    /**
     * Decrypt data.
     *
	 * @access protected
	 * @inheritdoc
     */
    protected function decrypt($data, ?string $secret = null, ?string $prefix = null)
    {
		$cryptor = new Encryption($data, $secret);
        return $cryptor->setPrefix($prefix)->decrypt();
    }

	/**
	 * Create nonce.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function createNonce($action = -1) : string
	{
	  	return Tokenizer::createNonce($action);
	}
}
