<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.3
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\Tokenizer;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\Arrayify;
use VanillePlugin\inc\Server;
use VanillePlugin\int\PluginNameSpaceInterface;

/**
 * Built-in authentication class,
 * @see Use JWT for external use is recommended.
 */
final class Authentication extends PluginOptions
{
	/**
	 * @access private
	 * @var array $tokens
	 */
	private $tokens = [];

    /**
     * @param PluginNameSpaceInterface $plugin
     */
    public function __construct(PluginNameSpaceInterface $plugin)
    {
        // Init plugin config
        $this->initConfig($plugin);

        // Init plugin tokens
		if ( !($this->tokens = $this->getPluginOption('tokens')) ) {
			$this->updatePluginOption('tokens',[],false);
		}
    }

	/**
	 * Check whether user is allowed.
	 * 
	 * @access public
	 * @param array $args
	 * @return bool
	 */
	public function isAllowed($args = [])
	{
		// Get public key
		if ( !($key = Server::getBearerToken()) ) {
			return false;
		}

		// Get user id by public key
		if ( ($id = $this->getUserId($key)) ) {

			// Validate public key
			if ( isset($this->tokens[$id]) ) {

				// Authenticate with public & secret
				$public = $this->tokens[$id]['public'];
				$secret = $this->tokens[$id]['secret'];
				$prefix = $args['prefix'] ?? '';
				$authenticated = $this->doAuthentication($public,$secret,$prefix);

				// Check authenticated user
				if ( $authenticated ) {
					// check role
					$role = $args['role'] ?? 'administrator';
					if ( Stringify::contains($authenticated->caps,$role) ) {
						return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * Add user public key.
	 * 
	 * @access public
	 * @param int $user
	 * @param string $key
	 * @return void
	 */
	public function addPublicKey($user, $key)
	{
		add_user_meta((int)$user,"_{$this->getNameSpace()}_public_key",$key);
	}

	/**
	 * Update user public key.
	 * 
	 * @access public
	 * @param int $user
	 * @param string $key
	 * @return void
	 */
	public function updatePublicKey($user, $key)
	{
		update_user_meta((int)$user,"_{$this->getNameSpace()}_public_key",$key);
	}

	/**
	 * Delete user public key.
	 * 
	 * @access public
	 * @param int $user
	 * @return void
	 */
	public function deletePublicKey($user)
	{
		delete_user_meta((int)$user,"_{$this->getNameSpace()}_public_key");
	}

	/**
	 * Get user Id using public key.
	 * 
	 * @access private
	 * @param string $key
	 * @return mixed
	 */
	private function getUserId($key)
	{
		$users = get_users([
			'meta_key'   => "_{$this->getNameSpace()}_public_key", 
			'meta_value' => $key
		]);
		$user = Arrayify::shift($users);
		if ( $user ) {
			return (int)$user->data->ID;
		}
		return false;
	}

	/**
	 * Authenticate user using public and secret key.
	 * 
	 * @access private
	 * @param string $public
	 * @param string $secret
	 * @param string $prefix
	 * @return mixed
	 */
	private function doAuthentication($public, $secret, $prefix = '')
	{
		if ( ($match = Tokenizer::match($public,$secret,$prefix)) ) {
			return $this->authenticate($match['username'],$match['password']);
		}
		return false;
	}
}
