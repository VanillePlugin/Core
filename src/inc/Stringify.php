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

namespace VanillePlugin\inc;

/**
 * Advanced custom I/O helper and string manipulation,
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
	 * @param mixed $subject
	 * @param int $count
	 * @return string
	 */
	public static function replace($search, $replace, $subject, ?int &$count = null)
	{
		return str_replace($search, $replace, $subject, $count);
	}

	/**
	 * Search replace substring(s).
	 * 
	 * @access public
	 * @param mixed $string
	 * @param mixed $replace
	 * @param mixed $offset
	 * @param mixed $length
	 * @return mixed
	 */
	public static function subReplace($string, $replace, $offset = 0, $length = null)
	{
		return substr_replace($string, $replace, $offset, $length);
	}

	/**
	 * Search replace string(s) using array.
	 * 
	 * @access public
	 * @param array $replace
	 * @param string $subject
	 * @return string
	 */
	public static function replaceArray(array $replace, string $subject) : string
	{
		foreach ($replace as $key => $value) {
			$subject = self::replace($key, $value, $subject);
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
		return preg_replace($regex, $replace, $subject, $limit, $count);
	}

	/**
	 * Remove string from other string.
	 * 
	 * @access public
	 * @param string $string
	 * @param string $subject
	 * @return string
	 */
	public static function remove(string $string, string $subject) : string
	{
		return (string)self::replace($string, '', $subject);
	}

	/**
	 * Remove sub string.
	 * 
	 * @access public
	 * @param string $string
	 * @param mixed $offset
	 * @param mixed $length
	 * @return string
	 */
	public static function subRemove(string $string, $offset = 0, $length = null) : string
	{
		if ( !$length ) {
			$length = strlen($string);
		}
		return self::subReplace($string, '', $offset, $length);
	}

	/**
	 * Remove string from other string using regex.
	 * 
	 * @access public
	 * @param string $regex
	 * @param string $subject
	 * @return string
	 */
	public static function removeRegex(string $regex, string $subject) : string
	{
		return (string)self::replaceRegex($regex, '', $subject);
	}

	/**
	 * Repeat string.
	 * 
	 * @access public
	 * @param string $string
	 * @param int $times
	 * @return string
	 */
	public static function repeat(string $string, int $times = 0) : string
	{
		return str_repeat($string, $times);
	}
	
	/**
	 * Lowercase string.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function lowercase(string $string) : string
	{
		return strtolower($string);
	}

	/**
	 * Uppercase string.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function uppercase(string $string) : string
	{
		return strtoupper($string);
	}

	/**
	 * Capitalize string.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function capitalize(string $string) : string
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
	public static function slugify(string $string) : string
	{
		$string = self::sanitizeTitle($string);
	  	return self::replace('_', '-', $string);
	}

	/**
	 * Search string.
	 *
	 * @access public
	 * @param mixed $string
	 * @param string $search
	 * @return bool
	 */
	public static function contains($string, string $search) : bool
	{
		if ( TypeCheck::isArray($string) ) {
			return Arrayify::inArray($search, $string);
		}
		if ( strpos((string)$string, $search) !== false ) {
			return true;
		}
		return false;
	}

	/**
	 * Split string.
	 *
	 * @access public
	 * @param string $string
	 * @param array $args, [regex, limit, flags, length]
	 * @return mixed
	 */
	public static function split(string $string, array $args = [])
	{
		if ( isset($args['regex']) ) {
			$limit = $args['limit'] ?? -1;
			$flags = $args['flags'] ?? 0;
			return preg_split($args['regex'], $string, $limit, $flags);
		}
		$length = $args['length'] ?? 1;
		return str_split($string, $length);
	}

	/**
	 * Check whether string is UTF8.
	 *
	 * @access public
	 * @param string $string
	 * @return bool
	 */
	public static function isUtf8($string) : bool
	{
		$check = wp_check_invalid_utf8($string);
		if ( $check !== $string ) {
			return false;
		}
		return seems_utf8($string);
	}

	/**
	 * Format path.
	 *
	 * @access public
	 * @param string $path
	 * @param bool $untrailing
	 * @return string
	 */
	public static function formatPath(string $path, bool $untrailing = false) : string
	{
		$path = wp_normalize_path($path);
		if ( $untrailing ) {
			$path = self::untrailingSlash($path);
		}
		return $path;
	}

	/**
	 * Format whitespaces,
	 * Including breaks.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function formatSpace(string $string) : string
	{
		return normalize_whitespace($string);
	}

	/**
	 * Format key (Sanitize alias).
	 * 
	 * @access public
	 * @param string $key
	 * @return string
	 */
	public static function formatKey(string $key) : string
	{
	    return self::sanitizeKey($key);
	}
	
	/**
	 * Remove slashes from value,
	 * Accept string and array.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return mixed
	 */
	public static function unSlash($value)
	{
		return wp_unslash($value);
	}
	
	/**
	 * Add slashes to value,
	 * Accept string and array.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return mixed
	 */
	public static function slash($value)
	{
	    return wp_slash($value);
	}

	/**
	 * Remove trailing slashes and backslashes if exist.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function untrailingSlash(string $string) : string
	{
	    return untrailingslashit($string);
	}

	/**
	 * Append trailing slashes.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function trailingSlash(string $string) : string
	{
	    return trailingslashit($string);
	}

	/**
	 * Strip slashes in quotes or single quotes,
	 * Removes double backslashs.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function stripSlash(string $string) : string
	{
		return stripslashes($string);
	}
	
	/**
	 * Strip slashes in quotes or single quotes,
	 * Removes double backslashs.
	 * (array, object, scalar).
	 * 
	 * @access public
	 * @param mixed $value
	 * @return mixed
	 */
	public static function deepStripSlash($value)
	{
		return stripslashes_deep($value);
	}

	/**
	 * Strip HTML tags in string,
	 * Including script and style.
	 * 
	 * @access public
	 * @param string $string
	 * @param bool $unbreak
	 * @return string
	 */
	public static function stripTag(string $string, bool $unbreak = false) : string
	{
		return wp_strip_all_tags($string, $unbreak);
	}

	/**
	 * Strip numbers in string,
	 * Using custom replace string.
	 * 
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function stripNumber(string $string, string $replace = '') : string
	{
		return (string)self::replaceRegex('/[0-9]+/', $replace, $string);
	}

	/**
	 * Strip special characters in string,
	 * Using custom replace string.
	 * 
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function stripChar(string $string, string $replace = '') : string
	{
		return (string)self::replaceRegex('/[^a-zA-Z0-9\s]/', $replace, $string);
	}

	/**
	 * Strip spaces in string,
	 * Using custom replace string.
	 * 
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function stripSpace(string $string, string $replace = '') : string
	{
		return (string)self::replaceRegex('/\s+/', $replace, trim($string));
	}

	/**
	 * Strip break in string,
	 * Using custom replace string.
	 * 
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function stripBreak(string $string, string $replace = '') : string
	{
		return (string)self::replaceRegex('/\r|\n/', $replace, $string);
	}

	/**
	 * Strip shortcodes in string.
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function unShortcode(string $string) : string
	{
		return strip_shortcodes($string);
	}

	/**
	 * Unserialize value only if it was serialized.
	 *
	 * @access public
	 * @param string $value
	 * @return mixed
	 */
	public static function unserialize(string $value)
	{
		return maybe_unserialize($value);
	}

	/**
	 * Serialize value if not serialized.
	 *
	 * @access public
	 * @param mixed $value
	 * @return mixed
	 */
	public static function serialize($value)
	{
		return maybe_serialize($value);
	}

	/**
	 * Check whether value is serialized.
	 *
	 * @access public
	 * @param string $value
	 * @return bool
	 */
	public static function isSerialized(string $value) : bool
	{
		return is_serialized($value, true);
	}

	/**
	 * Escape URL (URL toolkit),
	 * [Filter: clean_url],
	 * [[Filter: wp_allowed_protocols]].
	 * 
	 * @access public
	 * @param string $url
	 * @param array $protocols
	 * @param string $context
	 * @return string
	 */
	public static function escapeUrl(string $url, ?array $protocols = null, string $context = 'display') : string
	{
		return esc_url($url, $protocols, $context);
	}

	/**
	 * Escape HTML,
	 * [Filter: esc_html].
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeHTML(string $string) : string
	{
		return esc_html($string);
	}

	/**
	 * Escape XML,
	 * [Filter: esc_xml].
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeXML(string $string) : string
	{
		return esc_xml($string);
	}

	/**
	 * Escape JS,
	 * Escape single quotes and fixes line endings,
	 * [Filter: js_escape].
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeJS(string $string) : string
	{
		return esc_js($string);
	}

	/**
	 * Escape SQL.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeSQL(string $string) : string
	{
		return esc_sql($string);
	}

	/**
	 * Escape HTML attributes,
	 * [Filter: attribute_escape].
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeAttr(string $string) : string
	{
		return esc_attr($string);
	}

	/**
	 * Escape textarea,
	 * [Filter: esc_textarea].
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function escapeTextarea(string $string) : string
	{
		return esc_textarea($string);
	}

	/**
	 * Sanitize text,
	 * [Filter: sanitize_text_field].
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeText(string $string) : string
	{
		return sanitize_text_field($string);
	}

	/**
	 * Sanitize textarea,
	 * [Filter: sanitize_textarea_field].
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeTextarea(string $string) : string
	{
		return sanitize_textarea_field($string);
	}

	/**
	 * Sanitize title,
	 * [Filter: sanitize_title].
	 * 
	 * @access public
	 * @param string $string
	 * @param string $fallback
	 * @param string $context
	 * @return string
	 */
	public static function sanitizeTitle(string $string, ?string $fallback = null, string $context = 'save') : string
	{
		return sanitize_title($string, $fallback, $context);
	}

	/**
	 * Sanitize key,
	 * [Filter: sanitize_key].
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeKey(string $string)
	{
		return sanitize_key($string);
	}

	/**
	 * Sanitize email,
	 * [Filter: sanitize_email].
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeEmail(string $string) : string
	{
		return sanitize_email($string);
	}

	/**
	 * Sanitize hex color (with #).
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeColor(string $string) : string
	{
		return (string)sanitize_hex_color($string);
	}

	/**
	 * Sanitize HTML class,
	 * [Filter: sanitize_html_class].
	 * 
	 * @access public
	 * @param string $string
	 * @param string $fallback
	 * @return string
	 */
	public static function sanitizeHtmlClass(string $string, ?string $fallback = null) : string
	{
		return sanitize_html_class($string, $fallback);
	}

	/**
	 * Sanitize filename,
	 * Replacing whitespace with dashes,
	 * [Filter: sanitize_file_name],
	 * [Filter: sanitize_file_name_chars].
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeFilename(string $string) : string
	{
		return sanitize_file_name($string);
	}

	/**
	 * Sanitize mime type,
	 * [Filter: sanitize_mime_type].
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeMimeType(string $string) : string
	{
		return sanitize_mime_type($string);
	}

	/**
	 * Sanitize SQL 'order by' clause.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function sanitizeSqlOrder(string $string) : string
	{
		return (string)sanitize_sql_orderby($string);
	}

	/**
	 * Sanitize value based on option,
	 * [Filter: sanitize_option_{$key}].
	 * 
	 * @access public
	 * @param string $key
	 * @param string $value
	 * @return mixed
	 */
	public static function sanitizeOption(string $key, string $value)
	{
		return sanitize_option($key, $value);
	}

	/**
	 * Sanitize meta,
	 * [Filter: sanitize_{$type}_meta_{$key}].
	 * 
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param string $type
	 * @param string $sub
	 * @return mixed
	 */
	public static function sanitizeMeta(string $key, $value, string $type = 'post', ?string $sub = null)
	{
		return sanitize_meta($key, $value, $type, (string)$sub);
	}

	/**
	 * Sanitize username,
	 * [Filter: sanitize_user].
	 * 
	 * @access public
	 * @param string $user
	 * @param bool $strict
	 * @return string
	 */
	public static function sanitizeUser(string $user, bool $strict = true) : string
	{
		return sanitize_user($user, $strict);
	}

	/**
	 * Sanitize URL (URL toolkit),
	 * For database, redirection, non-encoded usage,
	 * [Filter: wp_allowed_protocols].
	 * 
	 * @access public
	 * @param string $url
	 * @param array $protocols
	 * @return string
	 */
	public static function sanitizeUrl(string $url, ?array $protocols = null) : string
	{
		return esc_url_raw($url, $protocols);
	}

	/**
	 * Sanitize HTML content (XSS),
	 * Expect unslashed content,
	 * [Filter: wp_kses_allowed_html],
	 * [Filter: wp_allowed_protocols].
	 * 
	 * @access public
	 * @param string $string
	 * @param mixed $html
	 * @param array $protocols
	 * @return string
	 */
	public static function sanitizeHTML(string $string, $html = 'post', ?array $protocols = null) : string
	{
		return wp_kses($string, $html, $protocols);
	}

	/**
	 * Match string using regex.
	 * 
	 * @access public
	 * @param string $regex
	 * @param string $string
	 * @param int $index
	 * @param int $flags
	 * @param int $offset
	 * @return mixed
	 */
	public static function match(string $regex, string $string, int $index = 0, int $flags = 0, int $offset = 0)
	{
		preg_match($regex, $string, $matches, $flags, $offset);
		if ( $index === -1 ) {
			return $matches;
		}
		return $matches[$index] ?? false;
	}

	/**
	 * Match all strings using regex (g).
	 * 
	 * @access public
	 * @param string $regex
	 * @param string $string
	 * @param int $index
	 * @param int $flags
	 * @param int $offset
	 * @return mixed
	 */
	public static function matchAll(string $regex, string $string, int $index = 0, int $flags = 0, int $offset = 0)
	{
		preg_match_all($regex, $string, $matches, $flags, $offset);
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
	public static function shuffle(string $string) : string
	{
		return str_shuffle($string);
	}

	/**
	 * Count chars in string.
	 * 
	 * @access public
	 * @param string $string
	 * @param int $mode
	 * @return mixed
	 */
	public static function count(string $string, int $mode = 0)
	{
		return count_chars($string, $mode);
	}

	/**
	 * Limit string (Without breaking words).
	 * 
	 * @access public
	 * @param string $string
	 * @param int $length
	 * @param int $offset
	 * @param string $suffix
	 * @return string
	 */
	public static function limit(string $string, int $length = 128, int $offset = 0, string $suffix = '...') : string
	{
		$limit = $string;

        $words = self::split($string, [
			'regex' => '/([\s\n\r]+)/u',
			'limit' => 0,
			'flags' => 2 // PREG_SPLIT_DELIM_CAPTURE
		]);

        if ( ($count = count($words)) ) {
			$strlen = 0;
			$last = $offset;
			for (; $last < $count; ++$last) {
				$strlen += strlen($words[$last]);
				if ( $strlen > $length ) {
					break;
				}
			}
			$limit = implode(Arrayify::slice($words, $offset, $last));
		}

		if ( empty($limit) ) {
			$limit = substr($string, $offset, $length);
		}

		if ( strlen($string) > $length ) {
			$limit .= " {$suffix}";
		}

		return trim($limit);
	}

	/**
	 * Filter string (Validation toolkit).
	 * 
	 * [DEFAULT: 516]
	 * 
	 * @access public
	 * @param mixed $value
	 * @param string $type
	 * @param int $filter
	 * @param mixed $options
	 * @return mixed
	 */
	public static function filter($value, ?string $type = 'name', int $filter = 516, $options = 0)
	{
		switch (self::lowercase((string)$type)) {
			case 'email':
				return filter_var($value, FILTER_SANITIZE_EMAIL);
				break;

			case 'name':
				return filter_var($value, FILTER_DEFAULT, FILTER_FLAG_NO_ENCODE_QUOTES);
				break;

			case 'subject':
				return filter_var($value, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
				break;

			case 'url':
			case 'link':
				return filter_var($value, FILTER_SANITIZE_URL);
				break;
		}

		return filter_var($value, $filter, $options);
	}

	/**
	 * Parse string (URL toolkit).
	 * 
	 * @access public
	 * @param string $string
	 * @param array $result
	 * @return mixed
	 */
	public static function parse(string $string, &$result = [])
	{
		parse_str($string, $result);
		return $result;
	}

	/**
	 * Parse URL (URL toolkit).
	 * 
	 * [SCHEME : 0]
	 * [HOST   : 1]
	 * [PATH   : 5]
	 * [QUERY  : 6]
	 * 
	 * @access public
	 * @param string $url
	 * @param int $component
	 * @return mixed
	 */
	public static function parseUrl(string $url, int $component = -1)
	{
		return parse_url($url, $component);
	}

	/**
	 * Build query args from string (URL toolkit).
	 * 
	 * [PHP_QUERY_RFC1738: 1]
	 * [PHP_QUERY_RFC3986: 2]
	 * 
	 * @access public
	 * @param mixed $args
	 * @param string $prefix, Numeric index for args (array)
	 * @param string $sep, Args separator
	 * @param int $enc, Encoding type
	 * @return string
	 */
	public static function buildQuery($args, string $prefix = '', ?string $sep = '&', int $enc = 1) : string
	{
		return http_build_query($args, $prefix, $sep, $enc);
	}

    /**
     * Generate MAC address.
     *
     * @access public
     * @return string
     */
    public static function generateMac() : string
    {
        $vals = [
            '0', '1', '2', '3', '4', '5', '6', '7',
            '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'
        ];
        $address = '';
        if ( count($vals) >= 1 ) {
            $address = ['00'];
            while (count($address) < 6) {
                shuffle($vals);
                $address[] = "{$vals[0]}{$vals[1]}";
            }
            $address = implode(':', $address);
        }
        return $address;
    }

	/**
	 * Format dash (hyphen) into underscore.
	 *
	 * @access public
	 * @param string $string
	 * @param bool $isGlobal
	 * @return string
	 */
	public static function undash(string $string, bool $isGlobal = false) : string
	{
		if ( $isGlobal ) {
			$string = self::uppercase($string);
		}
	    return self::replace('-', '_', $string);
	}

	/**
	 * Get basename with path format.
	 *
	 * @access public
	 * @param string $path
	 * @param string $suffix
	 * @return string
	 */
	public static function basename(string $path, string $suffix = '') : string
	{
		$path = self::replace('\\', '/', $path);
		return basename($path, $suffix);
	}

	/**
	 * Get break to line.
	 *
	 * @access public
	 * @return string
	 */
	public static function break() : string
	{
		return PHP_EOL;
	}
}
