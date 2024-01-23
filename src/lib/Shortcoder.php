<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\{
	Shortcode, Arrayify
};
use VanillePlugin\int\CallableInterface;

class Shortcoder extends View
{
	/**
	 * @access protected
	 * @var array $atts
	 * @var string $content
	 * @var string $tag
	 */
	protected $atts = [];
	protected $content = null;
	protected $tag = '';

	/**
	 * Init shortcoder.
	 */
	public function __construct(?CallableInterface $callable = null)
	{
		// Init plugin config
		$this->initConfig();

		// Set custom view callables
		if ( $callable ) {
			$this->setCallables(
				$callable->getCallables()
			);
		}
	}

	/**
	 * Set shortcode atts.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	public function setAttributes(array $atts = [])
	{
		$this->atts = Shortcode::formatAttributes($atts);
	}

	/**
	 * Set shortcode content.
	 *
	 * @access public
	 * @param string $atts
	 * @return void
	 */
	public function setContent(?string $content = null)
	{
	    $this->content = $content;
	}

	/**
	 * Set shortcode tag.
	 *
	 * @access public
	 * @param string $tag
	 * @return void
	 */
	public function setTag(?string $tag = null)
	{
		$this->tag = $tag;
	}
	
	/**
	 * Geenrate shortcode render.
	 *
	 * @access public
	 * @return string
	 */
	public function generate() : string
	{
        return 'template';
	}

	/**
	 * Show shortcode exception.
	 *
	 * @access protected
	 * @param string $error
	 * @return string
	 */
	protected function exception(string $error) : string
	{
		$error = $this->applyPluginFilter('shortcode-error', $error, $this->atts);
		if ( $error ) {
			return $this->assign('front/error', [
				'error' => $error,
				'atts'  => Arrayify::filter($this->atts)
			]);
		}
	}

	/**
	 * Get shortcode default atts.
	 * 
	 * @access protected
	 * @param string $type
	 * @return array
	 */
	protected function getDefaults(string $type) : array
	{
		$defaults = [];
		if ( $this->hasPluginFilter('defaults-shortcode-atts') ) {
			$defaults = $this->applyPluginFilter('defaults-shortcode-atts', $type, $defaults);
		}
		return $defaults;
	}

	/**
	 * Apply object default atts.
	 *
	 * @access protected
	 * @param string $type
	 * @return void
	 */
	protected function applyDefaults(string $type)
	{
		$this->atts = Shortcode::attributes(
			$this->getDefaults($type),
			$this->atts,
			$this->tag
		);
		$this->atts = $this->applyPluginFilter('shortcode-atts', $this->atts);
	}

	/**
	 * Set attribute.
	 *
	 * @access protected
	 * @param string $attr
	 * @param mixed $value
	 * @return void
	 */
	protected function setAttribute(string $attr, $value)
	{
		$this->atts[$attr] = $value;
	}
}
