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
 * Allowed to edit for plugin customization
 */

namespace winamaz\core\system\includes\thirdparty;

final class Htaccess
{

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	static public function activateCloaking(){
		if (function_exists('rocket_clean_domain') or is_plugin_active('wp-super-cache/wp-cache.php') ) {
            self::removeClockingRule();
			self::setClockingRule();
		}
		flush_rewrite_rules();
	}
	static public function desactivateCloaking(){
		remove_filter('mod_rewrite_rules', 'clockingRule',20);
		if (function_exists('rocket_clean_domain') or is_plugin_active('wp-super-cache/wp-cache.php') ) {
			self::removeClockingRule();
		}
		flush_rewrite_rules();
	}
	static public function clockingRule($rules){
		self::backupHtaccess($rules);
		$rules =  self::getClockingRule().$rules;
		return $rules;
	}
	static public function setClockingRule(){
		$rule = get_home_path().".htaccess";
		$content = file_get_contents ($rule);
		$content = self::getClockingRule() . $content ;
		self::updateHtaccess($content);
	}
	static public function removeClockingRule(){
		$rule = get_home_path().".htaccess";
		$content = file_get_contents ($rule);
		if( strpos( $content, '# BEGIN Winamaz' ) !== false) {
			$begin = strpos($content, '# BEGIN Winamaz');
			$end = strpos($content, '# END Winamaz') + strlen('# END Winamaz');
			$newContent = substr($content, 0, $begin);
			$newContent .= substr($content, $end, strlen($content));
			self::updateHtaccess($newContent);
		}
	}
	static public function backupHtaccess($content){
		$backup = WP_PLUGIN_DIR  .'/winamaz/core/storage/cache/backupHtaccess';
		if ( !file_exists($backup) ) {
			file_put_contents($backup, $content);
		}
	}
	static public function getClockingRule(){
		$homeRoot = static::extractUrlComponent( home_url(), PHP_URL_PATH );
		$homeRoot = isset( $homeRoot ) ? trailingslashit( $homeRoot ) : '/';
		$rule = WP_PLUGIN_DIR  .'/winamaz/core/storage/config/cloaking';
		$content = file_get_contents ($rule);
		$content = str_replace('HOME_ROOT', $homeRoot , $content);
		return $content;
	}
	static public function updateHtaccess($content){
		$file = fopen(get_home_path().".htaccess" , "w+" );
		fwrite($file, $content);
		fclose($file);
	}
	static public function extractUrlComponent( $url, $component ) {
		return _get_component_from_parsed_url_array( wp_parse_url( $url ), $component );
	}

}
