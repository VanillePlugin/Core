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

namespace VanillePlugin\inc;

final class Stringify
{
	/**
	 * @access public
	 * @param mixed $search
	 * @param mixed $replace
	 * @param string $subject
	 * @return string
	 */
	public static function replace($search, $replace, $subject)
	{
		return str_replace($search,$replace,(string)$subject);
	}

	/**
	 * @access public
	 * @param mixed $search
	 * @param mixed $replace
	 * @param mixed $offset
	 * @param mixed $length
	 * @return mixed
	 */
	public static function subreplace($search, $replace, $offset = 0, $length = null)
	{
		return substr_replace($search,$replace,$offset,$length);
	}

	/**
	 * @access public
	 * @param array $search
	 * @param array $replace
	 * @return string
	 */
	public static function replaceArray($replace, $subject)
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
	 * @param mixed $regex
	 * @param mixed $replace
	 * @param mixed $subject
	 * @param int $limit
	 * @param int $count
	 * @return mixed
	 */
	public static function replaceRegex($regex, $replace, $subject, $limit = -1, &$count = null)
	{
		return preg_replace($regex,$replace,$subject,$limit,$count);
	}

	/**
	 * @access public
	 * @param string $string
	 * @param int $times
	 * @return string
	 */
	public static function repeat($string, $times = 0)
	{
		return str_repeat((string)$string,$times);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function lowercase($string)
	{
		return strtolower((string)$string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function uppercase($string)
	{
		return strtoupper((string)$string);
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
	public static function toObject($array)
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
	public static function slugify(string $string)
	{
	  	return sanitize_title($string);
	}

	/**
	 * Search in string.
	 *
	 * @access public
	 * @param mixed $string
	 * @param string $search
	 * @return bool
	 */
	public static function contains($string, $search)
	{
		if ( TypeCheck::isArray($string) ) {
			return Arrayify::inArray($search,$string);
		}
		if ( strpos($string,$search) !== false ) {
			return true;
		}
		return false;
	}

	/**
	 * Split string.
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
			return preg_split($args['regex'],(string)$string,$limit,$flags);
		} else {
			$length = isset($args['length']) ? $args['length'] : 1;
			return str_split((string)$string,$length);
		}
	}

	/**
	 * Format Path.
	 *
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function formatPath($path)
	{
		return wp_normalize_path((string)$path);
	}

	/**
	 * @access public
	 * @param string $key
	 * @return string
	 */
	public static function formatKey($key)
	{
	    return sanitize_key((string)$key);
	}

	/**
	 * Encode string | Default encode string to UTF-8.
	 *
	 * @access public
	 * @param string $string
	 * @param string $to
	 * @param string $from
	 * @return string
	 */
	public static function encode($string, $from = 'ISO-8859-1', $to = 'UTF-8')
	{
		if ( self::getEncoding($string,$to,true) !== self::uppercase($to) ) {
			if ( ($encoded = @iconv($to,$from,$string)) ) {
				$string = $encoded;
			}
		}
		return $string;
	}

	/**
	 * Detect encoding.
	 *
	 * @access public
	 * @param string $string
	 * @param mixed $encodings
	 * @param bool $strict
	 * @return mixed
	 */
	public static function getEncoding($string, $encodings = null, $strict = true)
	{
		if ( TypeCheck::isFunction('mb_detect_encoding') ) {
			return mb_detect_encoding($string,$encodings,$strict);
		}
		return false;
	}

	/**
	 * Check UTF8.
	 *
	 * @access public
	 * @param string $string
	 * @return bool
	 */
	public static function isUTF8($string)
	{
		return seems_utf8($string);
	}

	/**
	 * Parse string.
	 *
	 * @access public
	 * @param string $string
	 * @param array $result
	 * @return mixed
	 */
	public static function parse($string, &$result = null)
	{
		parse_str((string)$string,$result);
		return $result;
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function unSlash($string)
	{
		return wp_unslash((string)$string);
	}
	
	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function slash($string)
	{
	    return '/' . self::unSlash($string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function untrailingSlash($string)
	{
	    return rtrim((string)$string,'/\\');
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function trailingSlash($string)
	{
	    return self::untrailingSlash($string) . '/';
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function slashStrip($string)
	{
		return self::deepMap($string,function($string) {
			return TypeCheck::isString($string) ? stripslashes($string) : $string;
		});
	}

	/**
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function numberStrip($string, $replace = '')
	{
		return self::replaceRegex('/[0-9]+/',$replace,(string)$string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function charStrip($string, $replace = '')
	{
		return self::replaceRegex('/[^a-zA-Z0-9\s]/',$replace,(string)$string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function spaceStrip($string, $replace = '')
	{
		return self::replaceRegex('/\s+/',$replace,trim((string)$string));
	}

	/**
	 * @access public
	 * @param string $string
	 * @param bool $break
	 * @return string
	 */
	public static function tagStrip($string, $break = false)
	{
		return wp_strip_all_tags((string)$string,$break);
	}

	/**
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function breakStrip($string, $replace = '')
	{
		return self::replaceRegex('/\r|\n/',$replace,(string)$string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function normalizeSpace($string)
	{
		return normalize_whitespace((string)$string);
	}

	/**
	 * Unserialize value only if it was serialized.
	 *
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
	 * @access public
	 * @param mixed $data
	 * @return mixed
	 */
	public static function serialize($data)
	{
		return maybe_serialize($data);
	}

	/**
	 * @access public
	 * @param array $data
	 * @param bool $strict
	 * @return bool
	 */
	public static function isSerialized($data, $strict = true)
	{
		return is_serialized($data,$strict);
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
		return esc_url((string)$url);
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
		return esc_url_raw((string)$url);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeText($string)
	{
		return sanitize_text_field((string)$string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeHTML($string)
	{
		return esc_html((string)$string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeJS($string)
	{
		return esc_js((string)$string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeAttr($string)
	{
		return esc_attr((string)$string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeSQL($string)
	{
		return esc_sql((string)$string);
	}

	/**
	 * Get an excerpt of text.
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
	public static function match($regex, $string, $index = 0, $flags = 0, $offset = 0)
	{
		preg_match($regex,(string)$string,$matches,$flags,$offset);
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
		preg_match_all($regex,(string)$string,$matches,$flags,$offset);
		if ( $index === -1 ) {
			return $matches;
		}
		return isset($matches[$index]) ? $matches[$index] : false;
	}

	/**
	 * Get random string.
	 * 
	 * @access public
	 * @param int $length
	 * @param string $char
	 * @return string
	 */
	public static function randomize($length = 10, $char = '')
	{
		if ( empty($char) ) {
			$char  = implode(range('a','f'));
			$char .= implode(range('0','9'));
		}
		$shuffled = self::shuffle((string)$char);
		return substr($shuffled,0,$length);
	}

	/**
	 * Shuffle string.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function shuffle($string)
	{
		return str_shuffle((string)$string);
	}

	/**
	 * @access private
	 * @param mixed $value
	 * @param callable $callback
	 * @return mixed
	 */
	private static function deepMap($value, $callback)
	{
	    if ( TypeCheck::isArray($value) ) {
	        foreach ( $value as $index => $item ) {
	            $value[$index] = self::deepMap($item, $callback);
	        }
	    } elseif ( TypeCheck::isObject($value) ) {
	        $vars = get_object_vars($value);
	        foreach ($vars as $name => $content) {
	            $value->$name = self::deepMap($content, $callback);
	        }
	    } else {
	        $value = call_user_func($callback, $value);
	    }
	    return $value;
	}
}
