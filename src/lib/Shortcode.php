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

namespace VanillePlugin\lib;

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\int\ShortcodeHelperInterface;

/**
 * Wrapper Class for Shortcode.
 * @todo
 */
final class Shortcode extends PluginOptions
{
	/**
	 * @param PluginNameSpaceInterface $plugin
	 */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);
	}

	/**
	 * Instance shortcodes.
	 * 
	 * @access public
	 * @param string $name
	 * @param string $keyword
	 * @param array $params
	 * @return mixed
	 */
	public static function instance($name = '', $keyword = '', $params = [])
	{
		$namespace = 'winamaz\core\system\functions\shortcode';
		$name = Stringify::lowercase($name);
		$shortcodes = [
			'single'     => "{$namespace}\Single",
			'multiple'   => "{$namespace}\Multiple",
			'simple'     => "{$namespace}\Simple",
			'listsimple' => "{$namespace}\Listsimple",
			'button'     => "{$namespace}\Button",
			'cta'        => "{$namespace}\Cta",
			'bestseller' => "{$namespace}\Bestseller",
			'variation'  => "{$namespace}\Variation",
			'newest'     => "{$namespace}\Newest",
			'bestprice'  => "{$namespace}\Bestprice",
			'ean'     	 => "{$namespace}\Ean",
			'table'      => "{$namespace}\Table",
			'voucher'    => "{$namespace}\Voucher",
			'coupon'     => "{$namespace}\Voucher" // Alias
		];
		if ( isset($shortcodes[$name]) ) {
			if ( TypeCheck::isClass($shortcodes[$name]) ) {
				$class = $shortcodes[$name];
				return new $class($keyword,$params);
			}
		}
		return null;
	}

	/**
	 * Instance
	 * 
	 * @access public
	 * @param string $name
	 * @param string $keyword
	 * @param array $params
	 * @return mixed
	 */
	public static function i($name = '', $keyword = '', $params = [])
	{
		return self::instance($name,$keyword,$params);
	}
}
