<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
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
use VanillePlugin\int\ShortcodedInterface;
use VanillePlugin\exc\ShortcodeException;

/**
 * Plugin shortcode manager.
 */
abstract class Shortcode extends View
{
	/**
	 * @access protected
	 */
	protected const PATH = 'shortcode';
	protected const TEMPLATE = 'front/shortcode';

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
	 * @var mixed $name, Shortcode name
	 * @var string $output, Shortcode output
	 */
	private $name;
	private $output;

	/**
	 * Init shortcode.
	 */
	public function __construct(?CallableInterface $callable = null)
	{
		// Set extended view callables
		$this->setCallables($callable);

		// Init output
		$this->setOutput();
	}

	/**
	 * Geenrate shortcode output.
	 *
	 * @access public
	 * @return string
	 */
	abstract public function generate() : string;

	/**
	 * Set attributes.
	 * [Filter: {plugin}-shortcode-global].
	 *
	 * @access protected
	 * @param array $atts
	 * @return void
	 */
	protected function setAttributes(array $atts = [])
	{
		$this->atts = $this->formatAtts($atts);
		if ( $this->applyPluginFilter('shortcode-global', false) ) {
			$namespace = $this->undash($this->getNameSpace());
			global ${$namespace};
			${$namespace} = $this->atts;
		}
	}

	/**
	 * Set attributes (Alias).
	 * [Filter: {plugin}-shortcode-global].
	 *
	 * @inheritdoc
	 */
	public function setAtts(array $atts = [])
	{
		$this->setAttributes($atts);
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
	 * Set shortcode content.
	 * [Filter: {plugin}-shortcode-nested].
	 * [Filter: {plugin}-shortcode-content].
	 *
	 * @access public
	 * @param string $content
	 * @return void
	 */
	public function setContent(?string $content = null)
	{
		unset($this->content);
		if ( $this->applyPluginFilter('shortcode-nested', false) ) {
			$content = $this->do($content);
		}
		$hook = 'shortcode-content';
		$this->content = $this->applyPluginFilter($hook, $content, $this->atts);
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
	public static function instance(string $name, ?string $path = self::PATH, ...$args)
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
	 * Set shortcode output.
	 *
	 * @access protected
	 * @param string $output
	 * @return void
	 */
	protected function setOutput(string $output = '')
	{
		unset($this->output);
		$this->output = $output;
	}

	/**
	 * Add shortcode output.
	 *
	 * @access protected
	 * @param string $output
	 * @return void
	 */
	protected function addOutput(string $output)
	{
		$this->output .= $output;
	}

	/**
	 * Get shortcode current output.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getCurrentOutput() : string
	{
		return (string)$this->output;
	}

	/**
	 * Get filtered shortcode output.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getOutput() : string
	{
		$hook = 'shortcode-output';
		return (string)$this->applyPluginFilter($hook, $this->output, $this->atts);
	}

	/**
	 * Get shortcode instance.
	 *
	 * @access protected
	 * @param string $name
	 * @param mixed $args
	 * @return mixed
	 */
	protected function get(string $name, ...$args)
	{
		$this->name = $name;
		$instance = self::instance($name, static::PATH, ...$args);
		$this->sanitizeAtts($instance);
		return $instance;
	}

	/**
	 * Get shortcode identifier keyword.
	 *
	 * @access protected
	 * @return mixed
	 */
	protected function getKeyword()
	{
		$keyword = $this->atts[$this->name] ?? null;
		if ( $keyword ) {
			$this->removeAttr($this->name);
		}
		return $keyword;
	}

	/**
	 * Get shortcode name.
	 *
	 * @access protected
	 * @return mixed
	 */
	protected function getName()
	{
		return $this->name;
	}

	/**
	 * Get shortcode instance name.
	 *
	 * @access protected
	 * @param ShortcodedInterface $instance
	 * @return string
	 */
	protected function getInstanceName(ShortcodedInterface $instance) : string
	{
		$class = new \ReflectionClass($instance);
		$name  = $this->basename($class->getName());
		return $this->slugify($name);
	}

	/**
	 * Sanitize shortcode attributes.
	 * [Filter: {plugin}-shortcode-default-atts].
	 *
	 * @access protected
	 * @param ShortcodedInterface $instance
	 * @return void
	 */
	protected function sanitizeAtts(ShortcodedInterface $instance)
	{
		$hook = 'shortcode-default-atts';
		$name = $this->getInstanceName($instance);

		$default = $this->setAttsValues($instance::atts());
		$default = (array)$this->applyPluginFilter($hook, $default, $name);

		$this->atts = $this->getAtts($default, $this->atts, $this->tag);
	}

	/**
	 * Add attributes.
	 *
	 * @access protected
	 * @param array $atts
	 * @return void
	 */
	protected function addAttributes(array $atts)
	{
		$this->atts = $this->mergeArray($atts, $this->atts);
	}

	/**
	 * Add attributes (Alias).
	 *
	 * @inheritdoc
	 */
	protected function addAtts(array $atts)
	{
		$this->addAttributes($atts);
	}

	/**
	 * Add single attribute.
	 *
	 * @access protected
	 * @param string $attr
	 * @param mixed $value
	 * @return void
	 */
	protected function addAttribute(string $attr, $value)
	{
		$this->atts[$attr] = $value;
	}

	/**
	 * Add single attribute (Alias).
	 *
	 * @inheritdoc
	 */
	protected function addAttr(string $attr, $value)
	{
		$this->addAttribute($attr, $value);
	}

	/**
	 * Remove single attribute.
	 *
	 * @access protected
	 * @param string $attr
	 * @return void
	 */
	protected function removeAttribute(string $attr)
	{
		unset($this->atts[$attr]);
	}

	/**
	 * Remove single attribute (Alias).
	 *
	 * @inheritdoc
	 */
	protected function removeAttr(string $attr)
	{
		$this->removeAttribute($attr);
	}

	/**
	 * Get output attributes.
	 * [Filter: {plugin}-shortcode-atts].
	 *
	 * @access protected
	 * @return array
	 */
	protected function getOutputAtts() : array
	{
		$hook = 'shortcode-atts';
		return $this->uniqueMultiArray(
			(array)$this->applyPluginFilter($hook, $this->atts)
		);
	}

	/**
	 * Get output template.
	 * [Filter: {plugin}-shortcode-output-template].
	 *
	 * @access protected
	 * @return string
	 */
	protected function getTemplate() : string
	{
		$hook = 'shortcode-output-template';
		$template = static::TEMPLATE;
		$template = "{$template}/{$this->name}";
		return (string)$this->applyPluginFilter($hook, $template, $this->name);
	}

	/**
	 * Get attributes.
	 *
	 * @inheritdoc
	 */
	protected function getAttributes(array $default = [], array $atts = [], ?string $tag = null) : array
	{
		return Core::getAtts($default, $atts, $tag);
	}

	/**
	 * Get attributes (Alias).
	 *
	 * @inheritdoc
	 */
	protected function getAtts(array $default = [], array $atts = [], ?string $tag = null) : array
	{
		return $this->getAttributes($default, $atts, $tag);
	}

	/**
	 * Get single attribute.
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
	 * Get single attribute (Alias).
	 *
	 * @inheritdoc
	 */
	protected function getAttr(string $attr)
	{
		return $this->getAttribute($attr);
	}

	/**
	 * Get hashed attributes.
	 *
	 * @access protected
	 * @return mixed
	 */
	protected function getHash()
	{
		return (new Hasher())->hash($this->atts);
	}

	/**
	 * Format attributes.
	 *
	 * @access protected
	 * @param array $atts
	 * @return array
	 */
	protected function formatAttributes(array $atts) : array
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
	 * Format attributes (Alias).
	 *
	 * @access protected
	 * @param array $atts
	 * @return array
	 */
	protected function formatAtts(array $atts) : array
	{
		return $this->formatAttributes($atts);
	}

	/**
	 * Format single attribute name.
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
	 * Show shortcode error.
	 * [Filter: {plugin}-shortcode-error].
	 * [Filter: {plugin}-shortcode-template].
	 *
	 * @access protected
	 * @param string $error
	 * @return mixed
	 */
	protected function error(string $error)
	{
		$hook  = 'shortcode-error';
		$error = $this->applyPluginFilter($hook, $error, $this->atts);

		if ( $error ) {

			$hook     = 'shortcode-template';
			$template = static::TEMPLATE;
			$template = "{$template}/error";
			$template = $this->applyPluginFilter($hook, $template, 'error');
	
			return $this->assign($template, [
				'error' => $error,
				'atts'  => $this->getOutputAtts()
			]);

		}
	}

	/**
	 * Assign content to shortcode.
	 *
	 * @inheritdoc
	 */
	protected function do(string $content, bool $ignore = false) : string
	{
		return Core::do($content, $ignore);
	}

	/**
	 * Check whether attribute is empty to allow override.
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
	 * Set attributes default values.
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
	 * Check shortcode has attribute (Not flag).
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
	 * Check shortcode has attribute (Alias).
	 *
	 * @inheritdoc
	 */
	protected function hasAttr(array $atts, string $attr) : bool
	{
		return $this->hasAttribute($atts, $attr);
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
	 * Get attribute value.
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
	 * Check attribute value.
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
	 * Check attribute disabled.
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
	 * Check attribute enabled.
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
