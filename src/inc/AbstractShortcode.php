<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use VanillePlugin\int\ShortcodeInterface;
use VanillePlugin\lib\View;

abstract class AbstractShortcode extends View implements ShortcodeInterface
{
	/**
	 * Return Shortcode Content
	 *
	 * Shortcode : [winamaz]
	 *
	 * @param array $params
	 * @param string $content
	 * @param string $tag
	 * @return string
	 */
	abstract public function callable($params = [], $content = null, $tag = '');

	/**
	 * Show shortcode exception error
	 *
	 * @param array errors
	 * @return string
	 */
	protected function exception($errors = [])
	{
		$errors = is_array($errors) ? $errors : [$errors];
		return $this->assign([
			'isLogged' => $this->isLoggedIn(),
			'errors'   => $errors
		], 'front/shortcode/error');
	}
}
