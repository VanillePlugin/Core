<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\{
	File,
	Json,
	Archive,
	Stringify,
	TypeCheck
};

/**
 * Define filesystem IO functions.
 */
trait TraitIO
{
	/**
	 * Check file.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isFile(string $path): bool
	{
		return File::isFile($path);
	}

	/**
	 * Check file readable.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isReadable(string $path, bool $fileType = false): bool
	{
		if ($fileType && !$this->isFile($path)) {
			return false;
		}
		return File::isReadable($path);
	}

	/**
	 * Check file writable.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isWritable(string $path, bool $fileType = false): bool
	{
		if ($fileType && !$this->isFile($path)) {
			return false;
		}
		return File::isWritable($path);
	}

	/**
	 * Read file.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function readFile(string $path, bool $inc = false, $context = null, int $offset = 0)
	{
		return File::r($path, $inc, $context, $offset);
	}

	/**
	 * Check directory.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function isDir(string $path): bool
	{
		return File::isDir($path);
	}

	/**
	 * Scan directory.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function scanDir(string $path = '.', int $sort = 0, array $except = []): array
	{
		return File::scanDir($path, $sort, $except);
	}

	/**
	 * Get all file lines.
	 * 
	 * @access public
	 * @inheritdoc
	 */
	public function getLines(string $path): array
	{
		return File::getLines($path);
	}

	/**
	 * Parse file lines using stream.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function parseLines(string $path, int $limit = 10): array
	{
		return File::parseLines($path, $limit);
	}

	/**
	 * Parse JSON file.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function parseJson(string $file, bool $isArray = false)
	{
		return Json::parse($file, $isArray);
	}

	/**
	 * Write file.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function writeFile(string $path, $input = '', bool $append = false): bool
	{
		return File::w($path, $input, $append);
	}

	/**
	 * Remove file.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeFile(string $path, $from = [])
	{
		if (!$this->secureRemove($path, $from)) {
			return false;
		}
		return File::remove($path);
	}

	/**
	 * Add directory.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addDir(string $path, int $p = 0755, bool $r = true, $c = null): bool
	{
		return File::addDir($path, $p, $r, $c);
	}

	/**
	 * Clear directory recursively.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function clearDir(string $path, $from = []): bool
	{
		if (!$this->secureRemove($path, $from)) {
			return false;
		}
		return File::clearDir($path);
	}

	/**
	 * Remove directory.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeDir(string $path, bool $clear = false, $from = []): bool
	{
		if (!$this->secureRemove($path, $from)) {
			return false;
		}
		return File::removeDir($path, $clear);
	}

	/**
	 * Compress archive.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function compressArchive(string $path, string $to = '', string $archive = ''): bool
	{
		return Archive::compress($path, $to, $archive);
	}

	/**
	 * Uncompress archive.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function uncompressArchive(string $archive, string $to = '', $remove = true): bool
	{
		return Archive::uncompress($archive, $to, $remove);
	}

	/**
	 * Secure remove.
	 *
	 * @access private
	 * @param string $path
	 * @param mixed $secure
	 * @return bool
	 */
	private function secureRemove(string $path, $secure = []): bool
	{
		if ($secure && !TypeCheck::isArray($secure)) {
			$secure = (string)$secure;
			$secure = [$secure];
		}

		$secure = ($secure) ? $secure : ['core/storage'];
		foreach ($secure as $include) {
			if (!Stringify::contains($path, $include)) {
				return false;
			}
		}

		return true;
	}
}
