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

namespace VanillePlugin\tr;

use VanillePlugin\inc\{
	Stringify, Arrayify, Converter,
    Json, Xml, TypeCheck, Validator
};

/**
 * Define formatting functions.
 */
trait TraitFormattable
{
    use TraitSerializable,
		TraitMappable;

	/**
	 * Format path.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function formatPath(string $path, bool $untrailing = false) : string
	{
		return Stringify::formatPath($path, $untrailing);
	}

	/**
	 * Format key.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function formatKey(string $key) : string
	{
		return Stringify::formatKey($key);
	}

	/**
	 * Format whitespaces.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function formatSpace(string $string) : string
	{
		return Stringify::formatSpace($string);
	}

	/**
	 * Strip spaces in string.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function stripSpace(string $string) : string
	{
		return Stringify::stripSpace($string);
	}

	/**
	 * Lowercase string.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function lowercase(string $string) : string
	{
		return Stringify::lowercase($string);
	}

	/**
	 * Uppercase string.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function uppercase(string $string) : string
	{
		return Stringify::uppercase($string);
	}

	/**
	 * Capitalize string.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function capitalize(string $string) : string
	{
		return Stringify::capitalize($string);
	}

	/**
	 * Camelcase string.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function camelcase(string $string) : string
	{
		return Stringify::camelcase($string);
	}

	/**
	 * Slugify string.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function slugify(string $string) : string
	{
		return Stringify::slugify($string);
	}

	/**
	 * Format dash (hyphen) into underscore.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function undash(string $string, bool $isGlobal = false) : string
	{
		return Stringify::undash($string, $isGlobal);
	}

	/**
	 * Remove slashes from value.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function unSlash($value)
	{
		return Stringify::unSlash($value);
	}

	/**
	 * Strip slashes in quotes or single quotes.
     *
	 * @access public
	 * @inheritdoc
	 */
	public function stripSlash($value)
	{
		return Stringify::deepStripSlash($value);
	}

	/**
	 * Add slashes to value.
	 * 
	 * @access public
	 * @inheritdoc
	 */
	public function slash($value)
	{
		return Stringify::slash($value);
	}

	/**
	 * Remove trailing slashes and backslashes if exist.
	 * 
	 * @access public
	 * @inheritdoc
	 */
	public function untrailingSlash(string $string) : string
	{
	    return Stringify::untrailingSlash($string);
	}

	/**
	 * Append trailing slashes.
	 * 
	 * @access public
	 * @inheritdoc
	 */
	public function trailingSlash(string $string) : string
	{
		return Stringify::trailingSlash($string);
	}

	/**
	 * Search string.
     *
	 * @access public
	 * @inheritdoc
	 */
	public function hasString($string, $search) : bool
    {
        return Stringify::contains($string, $search);
    }

	/**
	 * Remove string in other string.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function removeString(string $search, string $subject, bool $regex = false) : string
	{
        if ( $regex ) {
            return Stringify::removeRegex($search, $subject);
        }
		return Stringify::remove($search, $subject);
	}
	
	/**
	 * Search replace string(s).
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function replaceString($search, $replace, $subject, bool $regex = false)
    {
        if ( $regex ) {
            return Stringify::replaceRegex($search, $replace, $subject);
        }
        return Stringify::replace($search, $replace, $subject);
    }

	/**
	 * Search replace string(s) using array.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function replaceStringArray(array $replace, string $subject) : string
	{
		return Stringify::replaceArray($replace, $subject);
	}

	/**
	 * Match string using regex.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function matchString(string $regex, string $string, int $index = 0, int $flags = 0, int $offset = 0)
	{
		return Stringify::match($regex, $string, $index, $flags, $offset);
	}

	/**
	 * Match all strings using regex (g).
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function matchEveryString(string $regex, string $string, int $index = 0, int $flags = 0, int $offset = 0)
	{
		return Stringify::matchAll($regex, $string, $index, $flags, $offset);
	}

	/**
	 * Parse string.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function parseString(string $string, &$result = [])
	{
		return Stringify::parse($string, $result);
	}

	/**
	 * Limit string.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function limitString(?string $string, int $limit = 150) : string
	{
		return Stringify::limit((string)$string, $limit);
	}

	/**
	 * Get basename with path format.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function basename(string $path, string $suffix = '') : string
	{
		return Stringify::basename($path, $suffix);
	}

	/**
	 * Get break to line.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function breakString() : string
	{
		return Stringify::break();
	}

	/**
	 * Escape HTML.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function escapeHTML(string $string) : string
	{
		return Stringify::escapeHTML($string);
	}

	/**
	 * Escape HTML attribute.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function escapeAttr(string $string) : string
	{
		return Stringify::escapeAttr($string);
	}

	/**
	 * Escape textarea.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function escapeTextarea(string $string) : string
	{
		return Stringify::escapeTextarea($string);
	}

	/**
	 * Escape JS.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function escapeJS(string $string) : string
	{
		return Stringify::escapeJS($string);
	}

	/**
	 * Escape SQL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function escapeSQL(string $string) : string
	{
		return Stringify::escapeSQL($string);
	}

	/**
	 * Escape Url.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function escapeUrl(string $url) : string
	{
		return Stringify::escapeUrl($url);
	}

	/**
	 * Sanitize text field.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function sanitizeText(string $string) : string
	{
		return Stringify::sanitizeText($string);
	}

	/**
	 * Sanitize textarea field.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function sanitizeTextarea(string $string) : string
	{
		return Stringify::sanitizeTextarea($string);
	}

	/**
	 * Sanitize title.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function sanitizeTitle(string $string) : string
	{
		return Stringify::sanitizeTitle($string);
	}

	/**
	 * Sanitize email.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function sanitizeEmail(string $string) : string
	{
		return Stringify::sanitizeEmail($string);
	}

	/**
	 * Sanitize option value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function sanitizeOption(string $key, string $value) : string
	{
		return Stringify::sanitizeOption($key, $value);
	}

	/**
	 * Sanitize meta value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function sanitizeMeta(string $key, string $value) : string
	{
		return Stringify::sanitizeMeta($key, $value);
	}

	/**
	 * Sanitize username.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function sanitizeUsername(string $key, string $value) : string
	{
		return Stringify::sanitizeUsername($key, $value);
	}

	/**
	 * Sanitize url.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function sanitizeUrl(string $url) : string
	{
		return Stringify::sanitizeUrl($url);
	}

	/**
	 * Sanitize HTML content (XSS).
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function sanitizeHTML(string $string) : string
	{
		return Stringify::sanitizeHTML($string);
	}
	
	/**
	 * Check array item.
	 *
	 * @access public
	 * @inheritdoc
	 */
    public function inArray($value, array $array) : bool
    {
        return Arrayify::inArray($value, $array);
    }

	/**
	 * Merge arrays.
	 *
	 * @access public
	 * @inheritdoc
	 */
    public function mergeArray(array $array, array $arrays) : array
    {
        return Arrayify::merge($array, $arrays);
    }

	/**
	 * Filter array.
	 *
	 * @access public
	 * @inheritdoc
	 */
    public function filterArray(array $array, $callback = null, $mode = 0) : array
    {
        return Arrayify::filter($array, $callback, $mode);
    }

	/**
	 * Check array key.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function hasArrayKey($key, array $array) : bool
	{
		return Arrayify::hasKey($key, $array);
	}

	/**
	 * Get array keys.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function arrayKeys(array $array, $value = null, bool $search = false) : array
	{
		return Arrayify::keys($array, $value, $search);
	}

	/**
	 * Get single array key.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function arrayKey(array $array)
	{
		return Arrayify::key($array);
	}

	/**
	 * Get array values.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function arrayValues(array $array) : array
	{
		return Arrayify::values($array);
	}

	/**
	 * Shift array.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function shiftArray(array &$array) : array
	{
		return Arrayify::shift($array);
	}

	/**
	 * Get array diff.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function diffArray(array $array, array $arrays) : array
	{
		return Arrayify::diff($array, $arrays);
	}

    /**
     * Sort array.
     *
	 * @access public
	 * @inheritdoc
     */
    public function sortArray(array $array, $orderby = [], $order = 'ASC', bool $preserve = false)
	{
		return Arrayify::sort($array, $orderby, $order, $preserve);
	}

	/**
	 * Slice array.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function sliceArray(array $array, int $offset, ?int $length = null, bool $preserve = false) : array
	{
		return Arrayify::slice($array, $offset, $length, $preserve);
	}

	/**
	 * Unique array.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function uniqueArray(array $array, $flags = SORT_STRING) : array
	{
		return Arrayify::unique($array, $flags);
	}
	
	/**
	 * Unique arrays.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function uniqueMultiArray(array $array) : array
	{
		return Arrayify::uniqueMultiple($array);
	}
	
	/**
	 * Format array key case.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function formatKeyCase(array $array, int $case = CASE_LOWER) : array
	{
		return Arrayify::formatKeyCase($array, $case);
	}

	/**
	 * Push array.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function pushArray(array &$array, $values) : int
	{
		return Arrayify::push($array, $values);
	}

	/**
	 * Format array.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function formatArray(array $array) : array
	{
		return Arrayify::format($array);
	}

	/**
	 * Decode JSON.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function decodeJson(string $value, bool $isArray = false)
	{
		return Json::decode($value, $isArray);
	}

	/**
	 * Encode JSON without flags.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function encodeJson($value)
	{
		return Json::encode($value);
	}

	/**
	 * Encode JSON using flags.
     *
	 * @access public
	 * @inheritdoc
	 */
	public function formatJson($value, int $flags = 64|256, int $depth = 512)
	{
		return Json::format($value, $flags, $depth);
	}

	/**
	 * Parse XML string.
     *
	 * @access public
	 * @inheritdoc
	 */
	public function parseXml(string $xml, int $args = 16384|20908)
	{
		return Xml::parse($xml, $args);
	}

	/**
	 * Validate PHP module.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isModule(string $module) : bool
	{
		return Validator::isModule($module);
	}

	/**
	 * Validate server module.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isServerModule(string $module) : bool
	{
		return Validator::isServerModule($module);
	}

	/**
	 * Validate server config.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isConfig(string $name, $value) : bool
	{
		return Validator::isConfig($name, $value);
	}

	/**
	 * Validate version.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isVersion(string $v1, string $v2, string $operator = '==') : bool
	{
		return Validator::isVersion($v1, $v2, $operator);
	}

	/**
	 * Validate plugin file.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isPlugin(string $file) : bool
	{
		return Validator::isPlugin($file);
	}

	/**
	 * Validate plugin class.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isPluginClass(string $callable) : bool
	{
		return Validator::isPluginClass($callable);
	}

	/**
	 * Validate plugin version.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isPluginVersion(string $file, string $version, string $operator = '>=') : bool
	{
		return Validator::isPluginVersion($file, $version, $operator);
	}

	/**
     * Check value type.
     *
	 * @access public
	 * @inheritdoc
	 */
    public function isType($type, $value) : bool
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
     * Check object.
     *
	 * @access public
	 * @inheritdoc
	 */
    public function hasObject($type, $object, $item) : bool
    {
        switch ($this->lowercase($type)) {
            case 'interface':
                $item = Stringify::toInterface($item);
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
	 * @access public
	 * @inheritdoc
	 */
	public function toObject(array $array, $strict = false) : object
	{
		return Converter::toObject($array, $strict);
	}

	/**
	 * Convert object to array.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function toArray(object $object) : array
	{
	    return Converter::toArray($object);
	}

	/**
	 * Convert data to key.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function toKey($data) : string
	{
	    return Converter::toKey($data);
	}

	/**
	 * Convert dynamic types.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function toTypes($value)
	{
		return Converter::toTypes($value);
	}

	/**
	 * Format credentials.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function toCredentials($data)
	{
		if ( TypeCheck::isArray($data) ) {
			foreach ($data as $input => $value) {
				$data[$input] = Stringify::stripSpace($value);
			}

		} else {
			$data = Stringify::stripSpace($data);
		}
		
		return $data;
	}
}
