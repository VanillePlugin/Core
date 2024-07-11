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

namespace VanillePlugin\lib;

use VanillePlugin\inc\{
	TypeCheck, Shortcode as Core
};
use VanillePlugin\int\CallableInterface;
use VanillePlugin\exc\ShortcodeException;

/**
 * Plugin shortcode manager.
 */
class Shortcode extends View
{
	/**
	 * @access protected
	 * @var array $atts
	 * @var string $content
	 * @var string $tag
	 */
	protected $atts = [];
	protected $content;
	protected $tag;

	/**
	 * @access private
	 * @var string $output, Shortcode output
	 */
	private $output;

	/**
	 * Init shortcode.
	 */
	public function __construct(?CallableInterface $callable = null)
	{
		// Set custom view callables
		if ( $callable ) {
			$this->setCallables(
				$callable->getCallables()
			);
		}

		// Init output
		$this->setOutput();
	}

	/**
	 * Set shortcode content.
	 * [Filter: {plugin}-shortcode-nested].
	 * [Filter: {plugin}-shortcode-content].
	 *
	 * @access public
	 * @param string $atts
	 * @return void
	 */
	public function setContent(?string $content = null)
	{
		unset($this->content);
	    if ( $content ) {
			if ( $this->applyPluginFilter('shortcode-nested', false) ) {
				$content = $this->do($content);
			}
			$content = $this->applyPluginFilter('shortcode-content', $content);
		}
		$this->content = $this->applyPluginFilter('shortcode-content', $content, $this->atts);
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
		unset($this->tag);
		$this->tag = $tag;
	}

	/**
	 * Set shortcode output.
	 *
	 * @access public
	 * @param string $output
	 * @return void
	 */
	public function setOutput(string $output = '')
	{
		unset($this->output);
		$this->output = $output;
	}

	/**
	 * Add shortcode output.
	 *
	 * @access public
	 * @param string $output
	 * @return void
	 */
	public function addOutput(string $output)
	{
		$this->output .= $output;
	}

	/**
	 * Get shortcode output.
	 *
	 * @access public
	 * @return string
	 */
	public function getOutput() : string
	{
		return $this->output;
	}
	
	/**
	 * Geenrate shortcode output.
	 *
	 * @access public
	 * @return string
	 */
	public function generate() : string
	{
        return "{$this->getNameSpace()}";
	}

	/**
	 * Set shortcode atts.
	 * [Filter: {plugin}-shortcode-global].
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	public function setAtts(array $atts = [])
	{
		$this->atts = $this->formatAtts($atts);
		if ( $this->applyPluginFilter('shortcode-global', false) ) {
			$namespace = $this->undash($this->getNameSpace());
			global ${$namespace};
			${$namespace} = $this->atts;
		}
	}

	/**
	 * Instance shortcode.
	 *
	 * @access public
	 * @param string $name
	 * @param string $path
	 * @param mixed $args
	 * @return mixed
	 * @throws ShortcodeException
	 */
	public static function instance(string $name, $path = 'shortcode', ...$args)
	{
		$class = (new Loader())->i($path, $name, ...$args);
		if ( !TypeCheck::hasInterface($class, 'ShortcodedInterface') ) {
			throw new ShortcodeException(
				ShortcodeException::invalidInstance()
			);
		}
		return $class;
	}

	/**
	 * Show shortcode error.
	 * [Filter: {plugin}-shortcode-error].
	 * [Filter: {plugin}-shortcode-template].
	 *
	 * @access protected
	 * @param string $error
	 * @return string
	 */
	protected function error(string $error) : string
	{
		$error = $this->applyPluginFilter('shortcode-error', $error, $this->atts);
		if ( $error ) {
			$template = $this->applyPluginFilter('shortcode-template', 'front/error');
			return $this->assign($template, [
				'error' => $error,
				'atts'  => $this->filterArray($this->atts)
			]);
		}
	}

	/**
	 * Get shortcode default atts.
	 * [Filter: {plugin}-shortcode-default-atts].
	 *
	 * @access protected
	 * @param string $type
	 * @return array
	 */
	protected function getDefaults(string $type) : array
	{
		$defaults = [];
		if ( $this->hasPluginFilter('shortcode-default-atts') ) {
			$defaults = $this->applyPluginFilter('shortcode-default-atts', $type, $defaults);
		}
		return $defaults;
	}

	/**
	 * Apply object default atts.
	 * [Filter: {plugin}-shortcode-atts].
	 *
	 * @access protected
	 * @param string $type
	 * @return void
	 */
	protected function applyDefaults(string $type)
	{
		$this->atts = $this->getAtts(
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

	/**
	 * Set attribute (Alias).
	 *
	 * @access protected
	 * @param string $attr
	 * @param mixed $value
	 * @return void
	 */
	protected function setAttr(string $attr, $value)
	{
		$this->setAttribute($attr, $value);
	}

	/**
	 * Get attribute.
	 *
	 * @access protected
	 * @param string $attr
	 * @return mixed
	 */
	protected function getAttribute(string $attr)
	{
		return $this->atts[$attr] ?? null;
	}

	/**
	 * Get attribute (Alias).
	 *
	 * @access protected
	 * @param string $attr
	 * @return mixed
	 */
	protected function getAttr(string $attr)
	{
		return $this->getAttribute($attr);
	}

	/**
	 * Assign content to shortcode.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function do(string $content, bool $ignore = false) : string
	{
		return Core::do($content, $ignore);
	}

	/**
	 * Get shortcode attributes.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getAtts(array $default = [], array $atts = [], ?string $tag = null) : array
	{
		return Core::getAtts($default, $atts, $tag);
	}

	/**
	 * Check shortcode attribute empty to allow override.
	 * 
	 * @access protected
	 * @param array $atts
	 * @param string $attr
	 * @return bool
	 */
	protected function isEmpty(array $atts, string $attr) : bool
	{
		$attr = $this->formatAttrName($attr);
		$atts = $this->formatAtts($atts);
		if ( isset($atts[$attr]) ) {
			if ( $atts[$attr] === '0' || $atts[$attr] === 0 ) {
				return false;
			}
			return empty($atts[$attr]);
		}
		return false;
	}

	/**
	 * Format shortcode attributes.
	 *
	 * @access protected
	 * @param array $atts
	 * @return array
	 */
	protected function formatAtts(array $atts) : array
	{
		$attributes = [];
		$atts = $this->formatKeyCase($atts);
		foreach ($atts as $key => $value) {
			if ( $this->isType('string', $key) ) {
				$key = $this->formatAttrName($key);
			}
			$attributes[$key] = $value;
		}
		return $attributes;
	}

	/**
	 * Format shortcode attribute name.
	 * 
	 * @access protected
	 * @param string $attr
	 * @return string
	 */
	protected function formatAttrName(string $attr) : string
	{
		return $this->undash(
			$this->lowercase($attr)
		);
	}

	/**
	 * Format attribute value separator.
	 *
	 * @access protected
	 * @param string $value
	 * @param bool $strip, Strip space
	 * @return string
	 */
	protected function formatSep(string $value, bool $strip = false)
	{
		if ( $strip ) {
			$value = $this->stripSpace($value);
		}
		$value = $this->replaceString(';', ',', $value);
		$value = $this->replaceString('|', ',', $value);
		return $value;
	}

	/**
	 * Set shortcode attributes default values.
	 *
	 * @access protected
	 * @param array $atts
	 * @return array
	 */
	protected function setAttsValues(array $atts) : array
	{
		$values = [];
		foreach ($atts as $key => $name) {
			$values[$name] = '';
		}
		return $this->formatAtts($values);
	}

	/**
	 * Check shortcode has attribute (Not flag attribut).
	 * 
	 * @access protected
	 * @param array $atts
	 * @param string $attr
	 * @return bool
	 */
	protected function hasAttribute(array $atts, string $attr) : bool
	{
		$attr = $this->formatAttrName($attr);
		$atts = $this->formatAtts($atts);
		return isset($atts[$attr]) ? true : false;
	}

	/**
	 * Check shortcode has flag attribute.
	 *
	 * @access protected
	 * @param array $atts
	 * @param string $attr
	 * @return bool
	 */
	protected function hasFlag(array $atts, string $attr) : bool
	{
		$flags = [];
		$attr  = $this->formatAttrName($attr);
		foreach ($atts as $key => $name) {
			if ( $this->isType('int', $key) && $this->isType('string', $name) ) {
				$flags[] = $this->formatAttrName($name);
			}
		}
		return $this->inArray($attr, $flags);
	}
	
	/**
	 * Get shortcode attribute value.
	 * 
	 * @access protected
	 * @param array $atts
	 * @param string $attr
	 * @param string $type
	 * @return mixed
	 */
	protected function getValue(array $atts, string $attr, ?string $type = null)
	{
		$attr = $this->formatAttrName($attr);
		$atts = $this->formatAtts($atts);
		if ( isset($atts[$attr]) ) {
			$value = $atts[$attr];

			switch ($type) {
				case 'int':
				case 'integer':
					$value = intval($value);
					break;

				case 'float':
				case 'double':
					$value = floatval($value);
					break;

				case 'bool':
				case 'boolean':
					$value = boolval($value);
					break;
			}

			return $value;
		}

		return null;
	}

	/**
	 * Check shortcode attribute value.
	 * 
	 * @access protected
	 * @param array $atts
	 * @param string $attr
	 * @param mixed $value
	 * @return bool
	 */
	protected function hasValue(array $atts, string $attr, $value) : bool
	{
		$attr = $this->formatAttrName($attr);
		$atts = $this->formatAtts($atts);
		if ( isset($atts[$attr]) ) {
			$val = $atts[$attr];
			if ( $this->isType('string', $val) ) {
				$val = $this->lowercase($val);
			}
			if ( $this->isType('string', $value) ) {
				$value = $this->lowercase($value);
			}
			return ($val === $value);
		}
		return false;
	}

	/**
	 * Check shortcode attribute disabled.
	 * 
	 * @access protected
	 * @param array $atts
	 * @param string $attr
	 * @return bool
	 */
	protected function isDisabled(array $atts, string $attr) : bool
	{
		$attr = $this->formatAttrName($attr);
		$atts = $this->formatAtts($atts);
		if ( isset($atts[$attr]) ) {
			$value = $this->lowercase((string)$atts[$attr]);
			return $this->hasString(['off', 'no', 'non', 'false'], $value);
		}
		return false;
	}

	/**
	 * Check shortcode attribute enabled.
	 * 
	 * @access protected
	 * @param array $atts
	 * @param string $attr
	 * @return bool
	 */
	protected function isEnabled(array $atts, string $attr) : bool
	{
		$attr = $this->formatAttrName($attr);
		$atts = $this->formatAtts($atts);
		if ( isset($atts[$attr]) ) {
			$value = $this->lowercase((string)$atts[$attr]);
			return $this->hasString(['on', 'yes', 'oui', 'true'], $value);
		}
		return false;
	}
}
