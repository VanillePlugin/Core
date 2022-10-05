<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.9
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

namespace VanillePlugin\inc;

use \ZipArchive as ZIP;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
use \WP_Filesystem;

final class Archive
{
	/**
	 * Compress archive.
	 * 
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
	 * Uncompress archive.
	 * 
	 * @access public
	 * @param string $archive
	 * @param string $to
	 * @param bool $remove
	 * @return bool
	 */
	public static function uncompress($archive = '', $to = '', $remove = false)
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

			} elseif ( self::isGzip($archive) ) {
				return self::unGzip($archive);

			} else {
				WP_Filesystem();
				$result = unzip_file($archive,$to);
				if ( $result && !is_wp_error($result) ) {
					return true;
				}
			}

			if ( $remove ) {
				File::remove($archive);
			}
		}
		return false;
	}

	/**
	 * Check for valid gzip archive.
	 * 
	 * @access public
	 * @param string $archive
	 * @param int $length
	 * @return bool
	 */
	public static function isGzip($archive, $length = 4096) : bool
	{
		if ( File::isFile($archive) ) {
			$status = false;
			if ( ($gz = gzopen($archive,'r')) ) {
				$status = (bool)gzread($gz,$length);
			}
			gzclose($gz);
			return $status;
		}
		return false;
	}

	/**
	 * Uncompress gzip archive.
	 * 
	 * @access public
	 * @param string $archive
	 * @param int $length
	 * @return bool
	 */
	public static function unGzip($archive, $length = 4096) : bool
	{
		$status = false;
		if ( ($gz = gzopen($archive,'rb')) ) {
			$filename = Stringify::replace('.gz','',$archive);
			$to = fopen($filename,'wb');
			while ( !gzeof($gz) ) {
			    fwrite($to,gzread($gz,$length));
			}
			$status = true;
			fclose($to);
		}
		gzclose($gz);
		return $status;
	}
}
