<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.7
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use \ZipArchive as ZIP;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
use \WP_Filesystem;

final class Archive
{
	/**
	 * @access public
	 * @param string $path
	 * @param string $to
	 * @param string $archive
	 * @return bool
	 */
	public static function compress($path = '', $to = '', $archive = '')
	{
		if ( TypeCheck::isClass('ZipArchive') && !empty($path) ) {
			if ( empty($archive) ) {
				$archive = basename($path);
			}
			if ( empty($to) ) {
				$to = dirname($path);
			}
			$to = Stringify::formatPath($to,true);
			$to = "{$to}/{$archive}.zip";
			$zip = new ZIP();
			if ( $zip->open($to, ZIP::CREATE | ZIP::OVERWRITE) ) {
				if ( File::isDir($path) ) {
					$files = new RecursiveIteratorIterator(
					    new RecursiveDirectoryIterator($path),
					    RecursiveIteratorIterator::LEAVES_ONLY
					);
					foreach ($files as $name => $file) {
					    if ( !$file->isDir() ){
					        $p = $file->getRealPath();
					        $zip->addFile($p,basename($name));
					    }
					}
				} elseif ( File::isFile($path) ) {
					$zip->addFile($path,basename($path));
				}
				$zip->close();
				return true;
			}
		}
		return false;
	}

	/**
	 * @access public
	 * @param string $archive
	 * @param string $to
	 * @param bool $clear
	 * @return bool
	 */
	public static function uncompress($archive = '', $to = '', $clear = false)
	{
		if ( File::exists($archive) ) {
			if ( empty($to) ) {
				$to = dirname($archive);
			}
			if ( TypeCheck::isClass('ZipArchive') ) {
				$zip = new ZIP();
				$resource = $zip->open($archive);
				if ( $resource === true ) {
			  		$zip->extractTo($to);
			  		$zip->close();
			  		return true;
				}
			} else {
				\WP_Filesystem();
				$result = unzip_file($archive,$to);
				if ( $result && !is_wp_error($result) ) {
					return true;
				}
			}
			if ( $clear ) {
				var_dump($archive);die();
				@unlink($archive);
			}
		}
		return false;
	}
}
