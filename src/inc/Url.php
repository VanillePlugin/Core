<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.3
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Url 
{
	/**
	 * @access public
	 */
	public $protocol;

	public function __construct()
	{
		$this->getProtocol();
	}

	/**
	 * Redirect to URL
	 * 
	 * @param string|null $url
	 * @return void
	 */
	public static function redirectUrl($url = null)
	{
		if ( !empty($url) && !is_null($url) )
		{
			//header("Status: 301 Moved Permanently", false, 301);
			header("Location: {$url}/");
			exit();
		}
		else
		{
			header("Status: 301 Moved Permanently", false, 301);
			header("Location: /");
			exit();
		}
	}

	/**
	 * Get current URL
	 * 
	 * @param void
	 * @return string
	 */
	public static function current()
	{
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
		{
			return "https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		}
		else
		{
			return "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		}
	}

	/**
	 * Slugify string
	 *
	 * @param string $string
	 * @return string $string
	 */
	public static function slugify($string)
	{
		// replace non letter or digits by -
		$string = preg_replace('~[^\pL\d]+~u', '-', $string);

	  // transliterate
		$accents = [
		  'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
		  'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
		  'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
		  'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
		  'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'
		];

	  	$string = strtr( $string, $accents );
	  	$string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);

	  	// remove unwanted characters
	  	$string = preg_replace('~[^-\w]+~', '', $string);

	  	// trim
	  	$string = trim($string, '-');

	  	// remove duplicate -
	  	$string = preg_replace('~-+~', '-', $string);

	  	// lowercase
	  	$string = strtolower($string);

	  	if ( empty($string) ) return 'na';
	  	return $string;
	}

	/**
	 * get used protocol
	 *
	 * @param void
	 * @return string
	 */
	private function getProtocol()
	{
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
			$this->protocol = 'https://';
		} else
		{
			$this->protocol = 'http://';
		}
		return $this->protocol;
	}

	/**
	 * encode url
	 *
	 * @param string $url
	 * @return string
	 */
	public static function encode($url)
	{
		return urlencode($url);
	}
}
