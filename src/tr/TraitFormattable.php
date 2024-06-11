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

namespace VanillePlugin\tr;

use VanillePlugin\inc\{
	Stringify, Arrayify, Converter, Json, Xml, TypeCheck, Validator
};

trait TraitFormattable
{
    use TraitSerializable,
		TraitMapable;

	/**
	 * Format path.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function formatPath(string $path, bool $untrailing = false) : string
	{
		return Stringify::formatPath($path, $untrailing);
	}

	/**
	 * Format key.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function formatKey(string $key) : string
	{
		return Stringify::formatKey($key);
	}

	/**
	 * Format whitespaces.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function formatSpace(string $string) : string
	{
		return Stringify::formatSpace($string);
	}

	/**
	 * Strip spaces in string.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function stripSpace(string $string) : string
	{
		return Stringify::stripSpace($string);
	}

	/**
	 * Lowercase string.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function lowercase(string $string) : string
	{
		return Stringify::lowercase($string);
	}

	/**
	 * Uppercase string.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function uppercase(string $string) : string
	{
		return Stringify::uppercase($string);
	}

	/**
	 * Capitalize string.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function capitalize(string $string) : string
	{
		return Stringify::capitalize($string);
	}

	/**
	 * Slugify string.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function slugify(string $string) : string
	{
		return Stringify::slugify($string);
	}

	/**
	 * Format dash (hyphen) into underscore.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function undash(string $string, bool $isGlobal = false) : string
	{
		return Stringify::undash($string, $isGlobal);
	}

	/**
	 * Remove slashes from value.
	 * 
	 * @param mixed $value
	 * @return mixed
	 */
	protected function unSlash($value)
	{
		return Stringify::unSlash($value);
	}

	/**
	 * Strip slashes in quotes or single quotes.
     * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function stripSlash($value)
	{
		return Stringify::deepStripSlash($value);
	}

	/**
	 * Search string.
     * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasString($string, $search) : bool
    {
        return Stringify::contains($string, $search);
    }

	/**
	 * Remove string in other string.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeString(string $search, string $subject, bool $regex = false) : string
	{
        if ( $regex ) {
            return Stringify::removeRegex($search, $subject);
        }
		return Stringify::remove($search, $subject);
	}
	
	/**
	 * Search replace string(s),
     * Accept regex.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function replaceString($search, $replace, $subject, bool $regex = false)
    {
        if ( $regex ) {
            return Stringify::replaceRegex($search, $replace, $subject);
        }
        return Stringify::replace($search, $replace, $subject);
    }

	/**
	 * Search replace string(s) using array.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function replaceStringArray(array $replace, string $subject) : string
	{
		return Stringify::replaceArray($replace, $subject);
	}

	/**
	 * Match string using regex.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function matchString(string $regex, string $string, int $index = 0, int $flags = 0, int $offset = 0)
	{
		return Stringify::match($regex, $string, $index, $flags, $offset);
	}

	/**
	 * Match all strings using regex (g).
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function matchEveryString(string $regex, string $string, int $index = 0, int $flags = 0, int $offset = 0)
	{
		return Stringify::matchAll($regex, $string, $index, $flags, $offset);
	}

	/**
	 * Parse string.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function parseString(string $string, &$result = [])
	{
		return Stringify::parse($string, $result);
	}

	/**
	 * Limit string.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function limitString(string $string, int $limit = 150) : string
	{
		return Stringify::limit($string, $limit);
	}

	/**
	 * Get basename with path format.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function basename(string $path, string $suffix = '') : string
	{
		return Stringify::basename($path, $suffix);
	}

	/**
	 * Get break to line.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function breakString() : string
	{
		return Stringify::break();
	}

	/**
	 * Check array item.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
    protected function inArray($value, array $array) : bool
    {
        return Arrayify::inArray($value, $array);
    }

	/**
	 * Merge arrays.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
    protected function mergeArray(array $array, array $arrays) : array
    {
        return Arrayify::merge($array, $arrays);
    }

	/**
	 * Filter array.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
    protected function filterArray(array $array, $callback = null, $mode = 0) : array
    {
        return Arrayify::filter($array, $callback, $mode);
    }

	/**
	 * Check array key.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasArrayKey($key, array $array) : bool
	{
		return Arrayify::hasKey($key, $array);
	}

	/**
	 * Get array keys.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function arrayKeys(array $array) : array
	{
		return Arrayify::keys($array);
	}

	/**
	 * Get array values.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function arrayValues(array $array) : array
	{
		return Arrayify::values($array);
	}

	/**
	 * Shift array.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function shiftArray(array &$array) : array
	{
		return Arrayify::shift($array);
	}

	/**
	 * Get array diff.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function diffArray(array $array, array $arrays) : array
	{
		return Arrayify::diff($array, $arrays);
	}

    /**
     * Sort array.
     *
	 * @access protected
	 * @inheritdoc
     */
    protected function sortArray(array $array, $orderby = [], $order = 'ASC', bool $preserve = false)
	{
		return Arrayify::sort($array, $orderby, $order, $preserve);
	}

	/**
	 * Slice array.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function sliceArray(array $array, int $offset, ?int $length = null, bool $preserve = false) : array
	{
		return Arrayify::slice($array, $offset, $length, $preserve);
	}

	/**
	 * Unique array.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function uniqueArray(array $array, $flags = SORT_STRING) : array
	{
		return Arrayify::unique($array, $flags);
	}
	
	/**
	 * Unique arrays.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function uniqueMultiArray(array $array) : array
	{
		return Arrayify::uniqueMultiple($array);
	}

	/**
	 * Decode JSON.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function decodeJson(string $value, bool $isArray = false)
	{
		return Json::decode($value, $isArray);
	}

	/**
	 * Encode JSON without flags.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function encodeJson($value)
	{
		return Json::encode($value);
	}

	/**
	 * Encode JSON using flags.
     * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function formatJson($value, int $flags = 64|256, int $depth = 512)
	{
		return Json::format($value, $flags, $depth);
	}

	/**
	 * Parse XML string.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function parseXml(string $xml, int $args = 16384|20908)
	{
		return Xml::parse($xml, $args);
	}

	/**
	 * Validate PHP module.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isModule(string $extension) : bool
	{
		return Validator::isModule($extension);
	}

	/**
	 * Validate server config.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function isConfig(string $name, $value) : bool
	{
		return Validator::isConfig($name, $value);
	}

	/**
	 * Validate version.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isVersion(string $v1, string $v2, string $operator = '==') : bool
	{
		return Validator::isVersion($v1, $v2, $operator);
	}

	/**
	 * Validate plugin file.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isPlugin(string $file) : bool
	{
		return Validator::isPlugin($file);
	}

	/**
	 * Validate plugin class.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isPluginClass(string $callable) : bool
	{
		return Validator::isPluginClass($callable);
	}

	/**
	 * Validate plugin version.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function isPluginVersion(string $file, string $version, string $operator = '>=') : bool
	{
		return Validator::isPluginVersion($file, $version, $operator);
	}

	/**
     * Check value type.
     * 
	 * @access protected
	 * @inheritdoc
	 */
    protected function isType($type, $value) : bool
    {
        switch ($this->lowercase($type)) {
            case 'array':
                return TypeCheck::isArray($value);
                break;

			case 'object':
				return TypeCheck::isObject($value);
				break;

            case 'string':
                return TypeCheck::isString($value);
                break;

            case 'int':
                return TypeCheck::isInt($value);
                break;

            case 'numeric':
                return TypeCheck::isNumeric($value);
                break;

            case 'float':
            case 'double':
                return TypeCheck::isFloat($value);
                break;

            case 'bool':
                return TypeCheck::isBool($value);
                break;

            case 'false':
                return TypeCheck::isFalse($value);
                break;

            case 'true':
                return TypeCheck::isTrue($value);
                break;

            case 'null':
                return TypeCheck::isNull($value);
                break;

            case 'empty':
                return TypeCheck::isEmpty($value);
                break;

            case 'class':
                return TypeCheck::isClass($value);
                break;

			case 'interface':
                return TypeCheck::isInterface($value);
                break;

            case 'function':
                return TypeCheck::isFunction($value);
                break;

            case 'callable':
                return TypeCheck::isCallable($value);
                break;

            case 'email':
                return Validator::isValidEmail($value);
                break;

            case 'url':
                return Validator::isValidUrl($value);
                break;

            case 'date':
                return Validator::isValidDate($value);
                break;

            case 'ip':
                return Validator::isValidIp($value);
                break;
        }
        return false;
    }

	/**
     * Check object inherit.
     * 
	 * @access protected
	 * @inheritdoc
	 * @todo hasObject => hasItem
	 */
    protected function hasObject($type, $object, $item) : bool
    {
        switch ($this->lowercase($type)) {
            case 'interface':
                $i = $this->lowercase($item);
                if ( !$this->hasString($i, 'interface') ) {
                    $item = $this->capitalize($item);
                    $item = "{$item}Interface";
                }
                return TypeCheck::hasInterface($object, $item);
                break;

            case 'method':
                return TypeCheck::hasMethod($object, $item);
                break;

            case 'parent':
                return TypeCheck::isSubClassOf($object, $item);
                break;

			case 'child':
				return TypeCheck::isObject($item, $object);
				break;
        }
        return false;
    }

	/**
	 * Convert array to object.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function toObject(array $array, $strict = false) : object
	{
		return Converter::toObject($array, $strict);
	}

	/**
	 * Convert object to array.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function toArray(object $object) : array
	{
	    return Converter::toArray($object);
	}
}
