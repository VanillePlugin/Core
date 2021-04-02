<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.1
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\inc\Encryption;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\Server;
use VanillePlugin\int\PluginNameSpaceInterface;

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
			$this->updatePluginOption('tokens', []);
		}
    }

	/**
	 * @access public
	 * @param array $args
	 * @return bool
	 */
	public function isAllowed($args = [])
	{
		// Get public key
		if ( !($key = $this->getBearerToken()) ) {
			return false;
		}

		// Get user id by public key
		if ( ($id = $this->getUserID($key)) ) {

			// Validate public key
			if ( isset($this->tokens[$id]) ) {

				// Authenticate with public & secret
				$public = $this->tokens[$id]['public'];
				$secret = $this->tokens[$id]['secret'];
				$prefix = isset($args['prefix']) ? $args['prefix'] : '';
				$authenticated = $this->doAuthentication($public,$secret,$prefix);

				// Check authenticated user
				if ( $authenticated ) {
					// check role
					$role = isset($args['role']) ? $args['role'] : 'administrator';
					if ( Stringify::contains($authenticated->caps,$role) ) {
						return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * @access public
	 * @param int $user
	 * @param string $key
	 * @return void
	 */
	public function addPublicKey($user, $key)
	{
		add_user_meta(intval($user),"_{$this->getNameSpace()}_public_key",$key);
	}

	/**
	 * @access public
	 * @param int $user
	 * @param string $key
	 * @return void
	 */
	public function updatePublicKey($user, $key)
	{
		update_user_meta(intval($user),"_{$this->getNameSpace()}_public_key",$key);
	}

	/**
	 * @access public
	 * @param int $user
	 * @return void
	 */
	public function deletePublicKey($user)
	{
		delete_user_meta(intval($user),"_{$this->getNameSpace()}_public_key");
	}

	/**
	 * @access public
	 * @param string $user
	 * @param string $password
	 * @param string $prefix
	 * @return array
	 */
	public function generateToken($user, $password, $prefix = '')
	{
		$secret = md5(microtime().rand());
		$encryption = new Encryption("{$user}:{$password}",$secret);
		$encryption->setPrefix($prefix);
		return [
			'public' => $encryption->encrypt(),
			'secret' => $secret
		];
	}

	/**
	 * @access private
	 * @param void
	 * @return mixed
	 */
	public function getBearerToken()
	{
	    $headers = Server::getAuthorizationHeaders();
	    if ( !empty($headers) ) {
	        return Stringify::match('/Bearer\s(\S+)/',$headers);
	    }
	    return false;
	}

	/**
	 * @access public
	 * @param string $password
	 * @param string $hash
	 * @param int $user
	 * @return bool
	 */
	public static function isValidPassword($password, $hash, $user = null)
	{
		return wp_check_password($password,$hash,$user);
	}

	/**
	 * @access private
	 * @param string $key
	 * @return mixed
	 */
	private function getUserID($key)
	{
		$users = get_users([
			'meta_key'   => "_{$this->getNameSpace()}_public_key", 
			'meta_value' => $key
		]);
		$user = array_shift($users);
		if ($user) {
			return intval($user->data->ID);
		}
		return false;
	}

	/**
	 * @access private
	 * @param string $public
	 * @param string $secret
	 * @param string $prefix
	 * @return mixed
	 */
	private function doAuthentication($public = '', $secret = '', $prefix = '')
	{
		$encryption = new Encryption($public,$secret);
		$encryption->setPrefix($prefix);
		$credentials = explode(':',$encryption->decrypt());
		$username = isset($credentials[0]) ? $credentials[0] : '';
		$password = isset($credentials[1]) ? $credentials[1] : '';
		return $this->authenticate($username,$password);
	}
}
