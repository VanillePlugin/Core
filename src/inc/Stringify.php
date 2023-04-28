<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

/**
 * Advanced custom I/O helper,
 * @see https://wordpress.org/about/security/.
 */
final class Stringify
{
	/**
	 * Search replace string(s).
	 * 
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
	 * Search replace substring(s).
	 * 
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
	 * Search replace string(s) using array.
	 * 
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
	 * Search replace string(s) using regex.
	 * 
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
	 * Repeat string.
	 * 
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
	 * Lowercase string.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function lowercase($string)
	{
		return strtolower((string)$string);
	}

	/**
	 * Uppercase string.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function uppercase($string)
	{
		return strtoupper((string)$string);
	}

	/**
	 * Capitalize string.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function capitalize($string)
	{
		return ucfirst(self::lowercase($string));
	}

	/**
	 * Slugify string.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function slugify(string $string)
	{
		$string = self::sanitizeTitle($string);
	  	return self::replace('_','-',$string);
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
		if ( strpos((string)$string,$search) !== false ) {
			return true;
		}
		return false;
	}

	/**
	 * Split string.
	 *
	 * @access public
	 * @param string $string
	 * @param array $args, [regex,limit,flags,length]
	 * @return mixed
	 */
	public static function split($string, $args = [])
	{
		if ( isset($args['regex']) ) {
			$limit = $args['limit'] ?? -1;
			$flags = $args['flags'] ?? 0;
			return preg_split($args['regex'],(string)$string,$limit,$flags);
		}
		$length = $args['length'] ?? 1;
		return str_split((string)$string,$length);
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
	 * Detect string encoding.
	 *
	 * @access public
	 * @param string $string
	 * @param mixed $encodings
	 * @return mixed
	 */
	public static function getEncoding($string, $encodings = null)
	{
		if ( TypeCheck::isFunction('mb_detect_encoding') ) {
			return mb_detect_encoding($string,$encodings,true);
		}
		return false;
	}

	/**
	 * Check whether string is UTF8.
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
	 * format whitespaces,
	 * Including breaks.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function formatSpace($string)
	{
		return normalize_whitespace((string)$string);
	}

	/**
	 * @access public
	 * @param string $key
	 * @return string
	 * @todo
	 */
	public static function formatKey($key)
	{
	    return self::sanitizeKey($key);
	}
	
	/**
	 * Remove slashes from value,
	 * Accept string and array.
	 * 
	 * @access public
	 * @param mixed $data
	 * @return mixed
	 */
	public static function unSlash($data)
	{
		return wp_unslash($data);
	}
	
	/**
	 * Add slashes to value,
	 * Accept string and array.
	 * 
	 * @access public
	 * @param mixed $data
	 * @return mixed
	 */
	public static function slash($data)
	{
	    return wp_slash($data);
	}

	/**
	 * Remove trailing slashes and backslashes if exist.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function untrailingSlash($string)
	{
	    return untrailingslashit((string)$string);
	}

	/**
	 * Append trailing slashes.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function trailingSlash($string)
	{
	    return trailingslashit((string)$string);
	}

	/**
	 * Strip slashes from quotes.
	 * 
	 * @access public
	 * @param mixed $string
	 * @return string
	 */
	public static function stripSlash($string)
	{
		return wp_kses_stripslashes((string)$string);
	}

	/**
	 * Strip slashes from quotes,
	 * (array,object,scalar).
	 * 
	 * @access public
	 * @param mixed $data
	 * @return mixed
	 */
	public static function deepStripSlash($data)
	{
		return stripslashes_deep($data);
	}

	/**
	 * Strip HTML tags from string,
	 * Including script and style.
	 * 
	 * @access public
	 * @param string $string
	 * @param bool $break
	 * @return string
	 */
	public static function stripTag($string, $break = false)
	{
		return wp_strip_all_tags((string)$string,$break);
	}

	/**
	 * Strip numbers from string,
	 * Using custom replace string.
	 * 
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function stripNumber($string, $replace = '')
	{
		return self::replaceRegex('/[0-9]+/',$replace,(string)$string);
	}

	/**
	 * Strip characters from string,
	 * Using custom replace string.
	 * 
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function stripChar($string, $replace = '')
	{
		return self::replaceRegex('/[^a-zA-Z0-9\s]/',$replace,(string)$string);
	}

	/**
	 * Strip spaces from string,
	 * Using custom replace string.
	 * 
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function stripSpace($string, $replace = '')
	{
		return self::replaceRegex('/\s+/',$replace,trim((string)$string));
	}

	/**
	 * Strip break from string,
	 * Using custom replace string.
	 * 
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function stripBreak($string, $replace = '')
	{
		return self::replaceRegex('/\r|\n/',$replace,(string)$string);
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
	 * Serialize data if not serialized.
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
	 * @return bool
	 */
	public static function isSerialized($data)
	{
		return is_serialized($data,true);
	}

	/**
	 * Escape URL (URL toolkit).
	 * 
	 * Filter: clean_url
	 * Filter: wp_allowed_protocols
	 * 
	 * @access public
	 * @param string $url
	 * @param mixed $protocols
	 * @param string $context
	 * @return string
	 */
	public static function escapeUrl($url, $protocols = null, $context = 'display')
	{
		return esc_url((string)$url,$protocols,$context);
	}

	/**
	 * Escape HTML.
	 * 
	 * Filter: esc_html
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeHTML($string)
	{
		return esc_html((string)$string);
	}

	/**
	 * Escape XML.
	 * 
	 * Filter: esc_xml
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeXML($string)
	{
		return esc_xml((string)$string);
	}

	/**
	 * Escape JS,
	 * Escape single quotes and fixes line endings.
	 * 
	 * Filter: js_escape
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeJS($string)
	{
		return esc_js((string)$string);
	}

	/**
	 * Escape SQL.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeSQL($string)
	{
		return esc_sql((string)$string);
	}

	/**
	 * Escape HTML attributes.
	 * 
	 * Filter: attribute_escape
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeAttr($string)
	{
		return esc_attr((string)$string);
	}

	/**
	 * Escape textarea.
	 * 
	 * Filter: esc_textarea
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeTextarea($string)
	{
		return esc_textarea((string)$string);
	}

	/**
	 * Sanitize text.
	 * 
	 * Filter: sanitize_text_field
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeText($string)
	{
		return sanitize_text_field((string)$string);
	}

	/**
	 * Sanitize textarea.
	 * 
	 * Filter: sanitize_textarea_field
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeTextarea($string)
	{
		return sanitize_textarea_field((string)$string);
	}

	/**
	 * Sanitize title.
	 * 
	 * Filter: sanitize_title
	 * 
	 * @access public
	 * @param string $string
	 * @param string $fallback
	 * @param string $context
	 * @return string
	 */
	public static function sanitizeTitle($string, $fallback = '', $context = 'save')
	{
		return sanitize_title((string)$string,$fallback,$context);
	}

	/**
	 * Sanitize key.
	 * 
	 * Filter: sanitize_key
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeKey($string)
	{
		return sanitize_key((string)$string);
	}

	/**
	 * Sanitize email.
	 * 
	 * Filter: sanitize_email
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeEmail($string)
	{
		return sanitize_email((string)$string);
	}

	/**
	 * Sanitize hex color (with #).
	 * 
	 * @access public
	 * @param string $string
	 * @return mixed
	 */
	public static function sanitizeColor($string)
	{
		return sanitize_hex_color((string)$string);
	}

	/**
	 * Sanitize HTML class.
	 * 
	 * Filter: sanitize_html_class
	 * 
	 * @access public
	 * @param string $string
	 * @param string $fallback
	 * @return mixed
	 */
	public static function sanitizeHtmlClass($string, $fallback = '')
	{
		return sanitize_html_class((string)$string,$fallback);
	}

	/**
	 * Sanitize filename, replacing whitespace with dashes.
	 * 
	 * Filter: sanitize_file_name
	 * Filter: sanitize_file_name_chars
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeFilename($string)
	{
		return sanitize_file_name((string)$string);
	}

	/**
	 * Sanitize mime type.
	 * 
	 * Filter: sanitize_mime_type
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeMimeType($string)
	{
		return sanitize_mime_type((string)$string);
	}

	/**
	 * Sanitize SQL 'order by' clause.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeSQL($string)
	{
		return sanitize_sql_orderby((string)$string);
	}

	/**
	 * Sanitize value based on option.
	 * 
	 * Filter: sanitize_option_{$option}
	 * 
	 * @access public
	 * @param string $option
	 * @param string $value
	 * @return string
	 */
	public static function sanitizeOption($option, $value)
	{
		return sanitize_option((string)$option,(string)$value);
	}

	/**
	 * Sanitize meta.
	 * 
	 * Filter: sanitize_{$type}_meta_{$key}
	 * 
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param string $type
	 * @param string $subtype
	 * @return mixed
	 */
	public static function sanitizeMeta($key, $value, $type = 'post', $subtype = '')
	{
		return sanitize_meta($key,$value,$type,$subtype);
	}

	/**
	 * Sanitize username.
	 * 
	 * Filter: sanitize_user
	 * 
	 * @access public
	 * @param string $username
	 * @param bool $strict
	 * @return mixed
	 */
	public static function sanitizeUser($username, $strict = false)
	{
		return sanitize_user((string)$username,$strict);
	}

	/**
	 * Sanitize URL (URL toolkit),
	 * For database, redirection, non-encoded usage.
	 * 
	 * Filter: wp_allowed_protocols
	 * 
	 * @access public
	 * @param string $url
	 * @param mixed $protocols
	 * @return string
	 */
	public static function sanitizeUrl($url, $protocols = null)
	{
		return esc_url_raw((string)$url,$protocols);
	}

	/**
	 * Sanitize HTML content,
	 * Expect unslashed content.
	 * 
	 * Filter: wp_kses_allowed_html
	 * Filter: wp_allowed_protocols
	 * 
	 * @access public
	 * @param string $string
	 * @param mixed $html
	 * @param mixed $protocols
	 * @return string
	 */
	public static function sanitizeHTML($string, $html = 'post', $protocols = [])
	{
		return wp_kses((string)$string,$html,$protocols);
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
		return $matches[$index] ?? false;
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
		return $matches[$index] ?? false;
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
	 * Parse string (URL toolkit).
	 * 
	 * @access public
	 * @param string $string
	 * @param array $result
	 * @return mixed
	 */
	public static function parse($string, &$result = [])
	{
		parse_str((string)$string,$result);
		return $result;
	}

	/**
	 * Parse URL (URL toolkit).
	 * 
	 * @access public
	 * @param string $url
	 * @param int $component
	 * @return mixed
	 * 
	 * PHP_URL_SCHEME : 0
	 * PHP_URL_HOST : 1
	 * PHP_URL_PATH : 5
	 * PHP_URL_QUERY : 6
	 */
	public static function parseUrl($url, $component = -1)
	{
		return parse_url((string)$url,$component);
	}

	/**
	 * Build query args from string (URL toolkit).
	 * 
	 * @access public
	 * @param mixed $args
	 * @param string $prefix, Numeric index for args (array)
	 * @param string $sep, Args separator
	 * @param string $enc, Encoding type
	 * @return string
	 * 
	 * PHP_QUERY_RFC1738 : 1
	 */
	public static function buildQuery($args, $prefix = '', $sep = '', $enc = 1)
	{
		return http_build_query($args,$prefix,$sep,$enc);
	}
}
