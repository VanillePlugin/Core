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

namespace VanillePlugin\lib;

/**
 * Plugin custom assets manager.
 */
final class Asset
{
	use \VanillePlugin\VanillePluginConfig,
		\VanillePlugin\tr\TraitRequestable;

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
		// Set base dir
		$this->dir = $this->getAssetPath(
			$this->basename($dir)
		);

		// Set remote assets
		$this->assets = $this->getRemoteAssets();

		// Set cdn url
		$this->cdn = self::CDN;

		// Reset config
		$this->resetConfig();
	}

	/**
	 * Check asset lock.
	 *
	 * @access public
	 * @return bool
	 */
	public function hasAsset() : bool
	{
		return $this->isFile($this->dir . '/' . self::LOCK);
	}

	/**
	 * Lock asset.
	 *
	 * @access public
	 * @return bool
	 */
	public function lock() : bool
	{
		return $this->writeFile($this->dir . '/' . self::LOCK);
	}

	/**
	 * Unlock asset.
	 *
	 * @access public
	 * @return bool
	 */
	public function unlock() : bool
	{
		return $this->removeFile($this->dir . '/' . self::LOCK);
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
	 * Download remote assets.
	 *
	 * @access public
	 * @return void
	 */
	public function download()
	{
		// Allow CDN by default
		$cdn = true;

		// Get from remote
		if ( $this->remote ) {
			$api = $this->getHttpClient('GET', [
				'timeout'     => 30,
				'redirection' => 1
			]);
			$api->send($this->remote);
			if ( $api->getStatusCode() == 200 ) {
				$archive = "{$this->dir}/{$this->getFileName($this->remote)}";
				if ( $this->writeFile($archive, $api->getBody()) ) {
					$this->reset();
					$this->extract($archive);
					if ( $this->check() ) {
						$cdn = false;
						$this->lock();
					}
				}
			}
		}

		// Get from CDN
		if ( $cdn ) {
			$this->downloadCdn();
		}
	}

	/**
	 * Download CDN assets.
	 *
	 * @access private
	 * @return void
	 */
	private function downloadCdn()
	{
		$api = $this->getHttpClient('GET', [
			'timeout'     => 10,
			'redirection' => 1
		]);
		foreach ($this->assets as $asset => $files) {
			foreach ($files as $file) {
				$filename = "{$this->dir}/{$asset}/{$this->getFileName($file)}";
				if ( !$this->check($filename) ) {
					$api->send("{$this->cdn}/{$file}");
					if ( $api->getStatusCode() == 200 ) {
						$this->addDir("{$this->dir}/{$asset}");
						$this->writeFile($filename, $api->getBody());
					}
				}
			}
		}
		if ( $this->check() ) {
			$this->lock();
		}
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
			$this->removeFile("{$this->dir}/assets.zip");
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
		// Check for single asset
		if ( $this->isType('string', $path) && !empty($path) ) {
			return $this->isFile($path);
		}

		// Check for all assets
		foreach ($this->get() as $asset => $files) {
			foreach ($files as $file) {
				if ( !$this->isFile("{$this->dir}/{$asset}/{$file}") ) {
					return false;
					break;
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
		// Secured removing
		foreach ($this->get() as $asset => $files) {
			$dir = "{$this->dir}/{$asset}";
			if ( $this->isDir($dir) ) {
				if ( $this->hasString($dir, "/{$this->getNameSpace()}/") ) {
					$this->clearDir($dir);
					$this->removeDir($dir);
				}
			}
		}
	}

	/**
	 * Get asset filename from path.
	 *
	 * @access private
	 * @param string $path
	 * @return string
	 */
	private function getFileName($path)
	{
		return $this->basename($path);
	}

	/**
	 * Get assets.
	 *
	 * @access private
	 * @return array
	 */
	private function get()
	{
		$wrapper = [];
		foreach ($this->assets as $asset => $files) {
			$wrapper[$asset] = $this->mapArray(function($file){
				return $this->getFileName($file);
			}, $files);
		}
		return $wrapper;
	}
}
