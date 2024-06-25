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
    File, Arrayify, Json, Archive
};

trait TraitIO
{
	/**
	 * Check file.
	 *
	 * @access protected
	 * @inheritdoc
	 */
    protected function isFile(string $path) : bool
    {
		return File::isFile($path);
    }

	/**
	 * Check file readable.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isReadable(string $path) : bool
	{
		return File::isReadable($path);
	}

	/**
	 * Check file writable.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function isWritable(string $path) : bool
	{
		return File::isWritable($path);
	}

	/**
	 * Read file.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function readFile(string $path, bool $inc = false, $context = null, int $offset = 0)
	{
		return File::r($path, $inc, $context, $offset);
	}

	/**
	 * Write file.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function writeFile(string $path, $input = '', bool $append = false) : bool
	{
		return File::w($path, $input, $append);
	}

	/**
	 * Remove file.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removeFile(string $path)
	{
		return File::remove($path);
	}

    /**
     * Check directory.
	 *
	 * @access protected
	 * @inheritdoc
	 */
    protected function isDir(string $path) : bool
    {
    	return File::isDir($path);
    }

    /**
     * Add directory.
	 *
	 * @access protected
	 * @inheritdoc
	 */
    protected function addDir(string $path, int $p = 0755, bool $r = true, $c = null) : bool
    {
    	return File::addDir($path, $p, $r, $c);
    }

	/**
	 * Scan directory.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function scanDir(string $path = '.', int $sort = 0, array $except = []) : array
    {
    	return File::scanDir($path, $sort, $except);
    }

    /**
     * Clear directory recursively.
	 *
	 * @access protected
	 * @param array $secure
	 * @inheritdoc
	 */
    protected function clearDir(string $path, array $secure = []) : bool
	{
		if ( !$this->secureRemove($path, $secure) ) {
			return false;
		}
		return File::clearDir($path);
	}

    /**
     * Remove directory.
	 *
	 * @access protected
	 * @param array $secure
	 * @inheritdoc
	 */
    protected function removeDir(string $path, bool $clear = false, array $secure = []) : bool
    {
		if ( !$this->secureRemove($path, $secure) ) {
			return false;
		}
    	return File::removeDir($path, $clear);
    }

	/**
	 * Get all file lines.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function getLines(string $path) : array
	{
		return File::getLines($path);
	}
	
	/**
	 * Parse file lines using stream.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function parseLines(string $path, int $limit = 10) : array
	{
		return File::parseLines($path, $limit);
	}

	/**
	 * Parse JSON file.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function parseJson(string $file, bool $isArray = false)
	{
		return Json::parse($file, $isArray);
	}
	
	/**
	 * Compress archive.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function compressArchive(string $path, string $to = '', string $archive = '') : bool
	{
		return Archive::compress($path, $to, $archive);
	}

	/**
	 * Uncompress archive.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function uncompressArchive(string $archive, string $to = '', $remove = true) : bool
	{
		return Archive::uncompress($archive, $to, $remove);
	}

    /**
     * Secure remove.
	 *
	 * @access private
	 * @param string $path
	 * @param array $secure
	 * @return bool
	 */
    private function secureRemove(string $path, array $secure = []) : bool
    {
		$paths  = explode('/', $path);
		$secure = Arrayify::merge(['storage'], $secure);
		foreach ($secure as $include) {
			if ( !Arrayify::inArray($include, $paths) ) {
				return false;
			}
		}
		return true;
    }
}
