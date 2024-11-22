<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\Request;

/**
 * Plugin assets manager.
 */
final class Asset
{
	use \VanillePlugin\VanillePluginConfig;

	/**
	 * @access private
	 * @var string DIR
	 * @var string LOCK
	 * @var string CDN
	 */
	private const DIR  = 'vendor';
	private const LOCK = 'asset.lock';
	private const CDN  = 'https://cdnjs.cloudflare.com/ajax/libs';

	/**
	 * @access private
	 * @var string $dir, Assets base directory
	 * @var string $cdn, Public assets provider
	 * @var string $remote, Private asset provider
	 * @var object $assets, Assets list
	 */
	private $dir;
	private $cdn;
	private $remote;
	private $assets;

    /**
	 * Init asset.
     */
    public function __construct(string $dir = self::DIR)
	{
		$this->dir = $this->getAssetPath(
			$this->basename($dir)
		);
		$this->assets = $this->getRemoteAssets();
		$this->cdn = self::CDN;
	}

	/**
	 * Check asset path lock.
	 *
	 * @access public
	 * @return bool
	 */
	public function hasAsset() : bool
	{
		return $this->isFile("{$this->dir}/" . self::LOCK);
	}

	/**
	 * Lock asset path.
	 *
	 * @access public
	 * @return bool
	 */
	public function lock() : bool
	{
		return $this->writeFile("{$this->dir}/" . self::LOCK);
	}

	/**
	 * Unlock asset path.
	 *
	 * @access public
	 * @return bool
	 */
	public function unlock() : bool
	{
		$file = "{$this->dir}/" . self::LOCK;
		return $this->removeFile($file, $this->getRoot());
	}

	/**
	 * Set remote URL.
	 *
	 * @access public
	 * @param string $url
	 * @return object
	 */
	public function setRemote(string $url) : self
	{
		$this->remote = $url;
		return $this;
	}

	/**
	 * Set CDN endpoint URL.
	 *
	 * @access public
	 * @param string $url
	 * @return object
	 */
	public function setCdn(string $url) : self
	{
		$this->cdn = $url;
		return $this;
	}

	/**
	 * Download assets.
	 *
	 * @access public
	 * @return bool
	 */
	public function download() : bool
	{
		if ( !$this->isAdmin() ) {
			return false;
		}

		$downloaded = false;
		
		if ( $this->remote ) {

			$response = Request::do($this->remote, [
				'timeout'     => 30,
				'redirection' => 1
			]);

			if ( Request::getStatusCode($response) == 200 ) {

				$body = Request::getBody($response);
				$file = $this->getFileName($this->remote);
				$zip  = "{$this->dir}/{$file}";

				if ( $this->writeFile($zip, $body) ) {

					$this->reset();
					if ( $this->extract($zip) && $this->check() ) {
						$downloaded = true;
						$this->lock();
					}

				}
			}

		}

		if ( !$downloaded ) {
			return $this->downloadCdn();
		}

		return true;
	}

	/**
	 * Download CDN assets.
	 *
	 * @access private
	 * @return void
	 */
	private function downloadCdn()
	{
		foreach ($this->assets as $asset => $files) {
			foreach ($files as $file) {

				$filename = $this->getFileName($file);
				$filename = "{$this->dir}/{$asset}/{$filename}";

				if ( !$this->check($filename) ) {

					$remote   = "{$this->cdn}/{$file}";
					$response = Request::do($remote, [
						'timeout'     => 5,
						'redirection' => 2
					]);

					if ( Request::getStatusCode($response) == 200 ) {
						$body = Request::getBody($response);
						$this->addDir("{$this->dir}/{$asset}");
						$this->writeFile($filename, $body);
					}

				}

			}
		}

		if ( $this->check() ) {
			$this->lock();
			return true;
		}

		return false;
	}

	/**
	 * Extract remote assets archive.
	 *
	 * @access private
	 * @param string $archive
	 * @return bool
	 */
	private function extract(string $archive) : bool
	{
		if ( $this->uncompressArchive($archive, $this->dir, false) ) {
			$this->removeFile("{$this->dir}/assets.zip", $this->getRoot());
			return true;
		}
		return false;
	}

	/**
	 * Check extracted assets.
	 *
	 * @access private
	 * @param string $path
	 * @return bool
	 */
	private function check(?string $path = null) : bool
	{
		if ( $this->isType('string', $path) && !empty($path) ) {
			return $this->isFile($path);
		}

		foreach ($this->get() as $asset => $files) {
			foreach ($files as $file) {
				if ( !$this->isFile("{$this->dir}/{$asset}/{$file}") ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Reset assets.
	 *
	 * @access private
	 * @return void
	 */
	private function reset()
	{
		foreach ($this->get() as $asset => $files) {
			$dir = "{$this->dir}/{$asset}";
			$this->clearDir($dir, $this->getRoot());
			$this->removeDir($dir, $this->getRoot());
		}
	}

	/**
	 * Get asset filename from path.
	 *
	 * @access private
	 * @param string $path
	 * @return string
	 */
	private function getFileName(string $path) : string
	{
		return $this->basename($path);
	}

	/**
	 * Get assets.
	 *
	 * @access private
	 * @return array
	 */
	private function get() : array
	{
		$wrapper = [];
		foreach ($this->assets as $asset => $files) {
			$wrapper[$asset] = $this->map(function($file) {
				return $this->getFileName($file);
			}, $files);
		}
		return $wrapper;
	}
}
