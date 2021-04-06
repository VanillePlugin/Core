<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.3
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Stringify
{
	/**
	 * @access public
	 * @param array $search
	 * @param array $replace
	 * @param string $subject
	 * @return string
	 */
	public static function replace($search = [], $replace = [], $subject)
	{
		return str_replace($search,$replace,$subject);
	}

	/**
	 * @access public
	 * @param array $search
	 * @param array $replace
	 * @return string
	 */
	public static function replaceArray($replace = [], $subject)
	{
		if ( TypeCheck::isArray($replace) ) {
			foreach ($replace as $key => $value) {
				$subject = self::replace($key,$value,$subject);
			}
		}
		return $subject;
	}

	/**
	 * @access public
	 * @param string|array $regex
	 * @param string|array $replace
	 * @param string|array $subject
	 * @return mixed
	 */
	public static function replaceRegex($regex = '', $replace, $subject)
	{
		return preg_replace($regex,$replace,$subject);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function lowercase($string)
	{
		return strtolower($string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function uppercase($string)
	{
		return strtoupper($string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function capitalize($string)
	{
		return ucfirst(self::lowercase($string));
	}
	
	/**
	 * @access public
	 * @param array $array
	 * @return object
	 */
	public static function toObject($array = [])
	{
	    if ( empty($array) || !TypeCheck::isArray($array) ) {
	    	return false;
	    }
	    $obj = new \stdClass;
	    foreach ( $array as $key => $val ) {
	        $obj->{$key} = $val;
	    }
	    return (object)$obj;
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function slugify($string)
	{
	  	// Replace non letter or digits by -
	  	$slug = self::replaceRegex('~[^\pL\d]+~u','-',$string);
	  	// Transliterate
		$json = new Json(dirname(__FILE__).'/bin/accents.json');
		$accents = $json->parse(true);
	  	$slug = strtr($slug, $accents);
	  	$slug = self::encode($slug, 'ASCII//TRANSLIT//IGNORE');
	  	// Remove unwanted characters
	  	$slug = self::replaceRegex('~[^-\w]+~','',$slug);
	  	// Trim
	  	$slug = trim($slug, '-');
	  	// Remove duplicate -
	  	$slug = self::replaceRegex('~-+~','-',$slug);
	  	// Lowercase
	  	$slug = strtolower($slug);
	  	return !empty($slug) ? $slug : sanitize_title($string);
	}

	/**
	 * Search string
	 *
	 * @access public
	 * @param string|array $string
	 * @param string $search
	 * @return bool
	 */
	public static function contains($string, $search)
	{
		if ( TypeCheck::isArray($string) ) {
			return in_array($search, $string);
		}
		if ( strpos($string, $search) !== false ) {
			return true;
		}
		return false;
	}

	/**
	 * Split string
	 *
	 * @access public
	 * @param string $string
	 * @param array $args
	 * @return mixed
	 */
	public static function split($string, $args = [])
	{
		if ( isset($args['regex']) ) {
			$limit = isset($args['$limit']) ? $args['$limit'] : -1;
			$flags = isset($args['$flags']) ? $args['$flags'] : 0;
			return preg_split($args['regex'],$string,$limit,$flags);
		} else {
			$length = isset($args['length']) ? $args['length'] : 1;
			return str_split($string,$length);
		}
	}

	/**
	 * Format Path
	 *
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function formatPath($path)
	{
		return wp_normalize_path($path);
	}

	/**
	 * Format URL
	 *
	 * @access public
	 * @param string $url
	 * @return string
	 */
	public static function formatUrl($url)
	{
		return esc_url($url);
	}

	/**
	 * Escape SQL URL
	 *
	 * @access public
	 * @param string $url
	 * @return string
	 */
	public static function escapeUrl($url)
	{
		return esc_url_raw($url);
	}

	/**
	 * @access public
	 * @param string $key
	 * @return string
	 */
	public static function formatKey($key)
	{
	    return sanitize_key($key);
	}

	/**
	 * Encode string UTF-8
	 *
	 * @access public
	 * @param string $string
	 * @param string $to
	 * @param string $from
	 * @return string
	 */
	public static function encode($string, $from = 'ISO-8859-1', $to = 'UTF-8')
	{
		if ( self::lowercase($to) == 'utf-8' && self::lowercase($from) == 'iso-8859-1' ) {
			return utf8_encode($string);
		}
		return @iconv(self::uppercase($to), self::uppercase($from), $string);
	}

	/**
	 * Decode string ISO-8859-1
	 *
	 * @access public
	 * @param string $string
	 * @param string $to
	 * @param string $from
	 * @return string
	 */
	public static function decode($string, $from = 'UTF-8', $to = 'ISO-8859-1')
	{
		if ( self::lowercase($from) == 'utf-8' && self::lowercase($to) == 'iso-8859-1' ) {
			return utf8_decode($string);
		}
		return @iconv(self::uppercase($from), self::uppercase($to), $string);
	}

	/**
	 * Deeply strip slashes
	 *
	 * @see /reference/functions/wp_unslash/
	 * @access public
	 * @param mixed $data
	 * @return mixed
	 */
	public static function slashStrip($data)
	{
		return wp_unslash($data);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function numberStrip($string)
	{
		return self::replaceRegex('/[0-9]+/','',$string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function charStrip($string)
	{
		return self::replaceRegex('/[^a-zA-Z0-9\s]/','',$string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function spaceStrip($string)
	{
		return self::replaceRegex('/\s+/','',trim($string));
	}

	/**
	 * @access public
	 * @param string $string
	 * @param bool $break
	 * @return string
	 */
	public static function tagStrip($string, $break = false)
	{
		return wp_strip_all_tags($string,$break);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function breakStrip($string)
	{
		return self::replace("\n",' ',$string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function normalizeSpace($string)
	{
		return normalize_whitespace($string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeText($string)
	{
		return sanitize_text_field($string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeHTML($string)
	{
		return esc_html($string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeJS($string)
	{
		return esc_js($string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeAttr($string)
	{
		return esc_attr($string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeSQL($string)
	{
		return esc_sql($string);
	}

	/**
	 * Unserialize value only if it was serialized.
	 *
	 * @see /Function_Reference/maybe_serialize/
	 * @access public
	 * @param string $string
	 * @return mixed
	 */
	public static function unserialize($string)
	{
		return maybe_unserialize($string);
	}

	/**
	 * Serialize data, if needed.
	 *
	 * @see /Function_Reference/maybe_serialize/
	 * @access public
	 * @param mixed $data
	 * @return mixed
	 */
	public static function serialize($data)
	{
		return maybe_serialize($data);
	}

	/**
	 * Get an excerpt of text
	 *
	 * @access public
	 * @param string $content
	 * @param int $length
	 * @param string $more
	 * @return string
	 */
	public static function excerpt($content, $length = 40, $more = '[...]')
	{
		$excerpt = strip_tags(trim($content));
		$words = str_word_count( $excerpt, 2 );
		if ( count($words) > $length ) {
			$words = array_slice($words,0,$length,true);
			end($words);
			$position = key($words) + strlen(current($words));
			$excerpt = substr($excerpt,0,$position) . ' ' . $more;
		}
		return $excerpt;
	}
	
	/**
	 * @access public
	 * @param mixed $number
	 * @param int $decimals
	 * @param string $dSep Decimals Separator
	 * @param string $tSep Thousands Separator
	 * @return float
	 */
	public static function toMoney($number, $decimals = 2, $dSep = '.', $tSep = ' ')
	{
		return number_format((float)$number,$decimals,$dSep,$tSep);
	}

	/**
	 * @access public
	 * @param string $regex
	 * @param string $string
	 * @param int $index
	 * @param int $flags
	 * @param int $offset
	 * @return mixed
	 */
	public static function match($regex, $string, $index = 1, $flags = 0, $offset = 0)
	{
		preg_match($regex,$string,$matches,$flags,$offset);
		if ( $index === -1 ) {
			return $matches;
		}
		return isset($matches[$index]) ? $matches[$index] : false;
	}

	/**
	 * @access public
	 * @param string $regex
	 * @param string $string
	 * @param int $index
	 * @param int $flags
	 * @param int $offset
	 * @return mixed
	 */
	public static function matchAll($regex, $string, $index = 0, $flags = 0, $offset = 0)
	{
		preg_match_all($regex,$string,$matches,$flags,$offset);
		if ( $index === -1 ) {
			return $matches;
		}
		return isset($matches[$index]) ? $matches[$index] : false;
	}
}
