<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.9
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin;

use VanillePlugin\inc\Exception;

class VanillePluginException extends Exception
{
	/**
	 * @access public
	 * @var int $code
	 * @return string
	 */
	public function get($code = 1)
	{
		$header  = "[VanillePluginException][{$code}]";
		$message = "{$header} Error : {$this->getError($code)}";
		$source  = defined('VANILLEPLUGIN_EXCEPTION_SOURCE')
		? VANILLEPLUGIN_EXCEPTION_SOURCE : false;
		if ( $source ) {
			$message .= "<br>{$header} Line : {$this->getLine()} in {$this->getFile()}";
		}
		if ( $this->getMessage() ) {
			$message .= " ({$this->getMessage()})";
		}
		return $message;
	}

	/**
	 * @access private
	 * @var int $code
	 * @return string
	 */
	private function getError($code)
	{
		$domain = defined('VANILLEPLUGIN_EXCEPTION_DOMAIN')
		? VANILLEPLUGIN_EXCEPTION_DOMAIN : '';
		$code = intval($code);
		switch ($code) {
			case 1:
				return __('Invalid Plugin Namepsace', $domain);
				break;
			case 2:
				return __('Invalid Plugin Configuration', $domain);
				break;
		}
		return __('Unknown error', $domain);
	}
}
