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
	Upload, File
};

/**
 * Define uploading functions.
 */
trait TraitUploadable
{
	/**
	 * Get upload directory.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getUploadDir(?string $sub = null) : string
	{
		return Upload::getDir($sub);
	}

	/**
	 * Get upload URL.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getUploadUrl(?string $sub = null) : string
	{
		return Upload::getUrl($sub);
	}

	/**
     * Move uploaded file.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function moveUpload(string $from, string $to) : bool
	{
        return Upload::move($from, $to);
	}

	/**
     * Handle uploaded files.
     *
	 * @access protected
	 * @inheritdoc
	 */
	protected function doUpload(array &$files, string $path, ?array $types = []) : bool
	{
		$count = 0;
		$files = Upload::sanitize($files, $types);

		if ( !File::exists($path) ) {
			File::addDir($path);
		}

		foreach ($files as $key => $file) {

			$temp = $file['temp'];
			$path = "{$path}/{$file['path']}";

			if ( $this->moveUpload($temp, $path) ) {
				$files[$key]['path'] = $path;
				++$count;
			}

		}

		return (bool)$count;
	}
}
