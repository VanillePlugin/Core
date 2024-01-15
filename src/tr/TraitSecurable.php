<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\{
	Encryption, Tokenizer
};

trait TraitSecurable
{
    /**
     * Get token.
     * 
	 * @access protected
	 * @inheritdoc
     */
    protected function getToken(string $user, string $pswd, ?string $prefix = null) : array
	{
		return Tokenizer::get($user, $pswd, $prefix);
	}

    /**
     * Match token.
     * 
	 * @access protected
	 * @inheritdoc
     */
    protected function matchToken(string $public, string $secret, ?string $prefix = null)
	{
		return Tokenizer::match($public, $secret, $prefix);
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

	/**
	 * Check nonce.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function checkNonce(string $nonce, $action = -1) : bool
	{
		return Tokenizer::checkNonce($nonce, $action);
	}

	/**
	 * Check AJAX nonce.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function checkAjaxNonce($nonce, $action = -1) : bool
	{
		return Tokenizer::checkAjaxNonce($action, $nonce);
	}

    /**
     * Encode base64.
     *
	 * @access protected
	 * @inheritdoc
     */
    protected function base64(string $value, int $loop = 1) : string
	{
		return Tokenizer::base64($value, $loop);
	}

    /**
     * Decode base64.
     *
	 * @access protected
	 * @inheritdoc
     */
    protected function unbase64(string $value, int $loop = 1) : string
	{
		return Tokenizer::unbase64($value, $loop);
	}

    /**
     * Get unique Id.
     *
	 * @access protected
	 * @inheritdoc
     */
    protected function getUniqueId() : string
	{
		return Tokenizer::getUniqueId();
	}

    /**
     * Generate token.
     * 
	 * @access protected
	 * @inheritdoc
     */
    protected function generateToken(int $length = 16, bool $special = false) : string
	{
		return Tokenizer::generate($length, $special);
	}

    /**
     * Get encryption object.
     *
	 * @access protected
	 * @inheritdoc
     */
    protected function getCryptor($data, ?string $key = 'v8t1pQ92PN', ?string $vector = 'ZRfvSPsFQ', ?int $length = 16) : Encryption
    {
        return new Encryption($data, $key, $vector, $length);
    }

    /**
     * Encrypt data.
     *
	 * @access protected
	 * @inheritdoc
     */
    protected function encrypt($data) : string
    {
        return (new Encryption($data))->bypass()->encrypt();
    }

    /**
     * Decrypt data.
     *
	 * @access protected
	 * @inheritdoc
     */
    protected function decrypt($data)
    {
        return (new Encryption($data))->decrypt();
    }
}
