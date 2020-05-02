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

final class Text
{
	/**
	 * Get an excerpt of text
	 *
	 * @param string $content, int $length, string $more
	 * @return string
	 */
	public static function excerpt($content, $length = 40, $more = '[...]' )
	{
		$excerpt = strip_tags( trim( $content ) );
		$words = str_word_count( $excerpt, 2 );
		if ( count( $words ) > $length ) {
			$words = array_slice( $words, 0, $length, true );
			end( $words );
			$position = key( $words ) + strlen( current( $words ) );
			$excerpt = substr( $excerpt, 0, $position ) . ' ' . $more;
		}
		return $excerpt;
	}

	/**
	 * Get an excerpt of text
	 *
	 * @param string $subject, array|string $search, string|null $replace
	 * @return string
	 */
	public static function replace($subject, $search, $replace = null)
	{
		if ( is_array($search) ) {
			if (is_null($replace)) return str_replace(array_keys($search), $search, $subject);
			else return str_replace(array_keys($search), array_values($replace), $subject);
		} else {
			return str_replace($search, $replace, $subject);
		}
	}

	/**
	 * @param mixed $number
	 * @param mixed $decimals
	 * @param mixed $point
	 * @param mixed $sep
	 * @return string
	 */
	public static function money($number, $decimals = 2, $point = '.', $sep = '')
	{
		return number_format($number,$decimals,$point,$sep);
	}
}
