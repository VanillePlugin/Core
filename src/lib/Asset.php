<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.2
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\File;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\Arrayify;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\inc\Archive;
use VanillePlugin\inc\API;
use VanillePlugin\int\PluginNameSpaceInterface;

final class Asset extends PluginOptions
{
	/**
	 * @access public
	 */
	const CDN = 'https://cdnjs.cloudflare.com/ajax/libs';

	/**
	 * @access private
	 * @var string $dir
	 * @var string $cdn
	 * @var string $remote
	 * @var object $assets
	 */
	private $dir;
	private $cdn;
	private $remote;
	private $assets;

    /**
     * @param PluginNameSpaceInterface $plugin
     */
    public function __construct(PluginNameSpaceInterface $plugin)
	{
        // Init plugin config
        $this->initConfig($plugin);
		// Set remote assets
		$this->assets = $this->getRemoteAsset();
		// Set cdn url
		$this->cdn = self::CDN;
	}

	/**
	 * Check whether has asset lock.
	 *
	 * @access public
	 * @param void
	 * @return bool
	 */
	public function hasAssets()
	{
		return File::exists("{$this->dir}/asset.lock");
	}

	/**
	 * Lock asset.
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function lock()
	{
		File::w("{$this->dir}/asset.lock");
	}

	/**
	 * Set remote URL.
	 *
	 * @access public
	 * @param string $remote
	 * @return object
	 */
	public function setRemote($url)
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
	public function setCDN($url)
	{
		$this->cdn = $url;
		return $this;
	}

	/**
	 * Set assets dir.
	 *
	 * @access public
	 * @param string $dir
	 * @return object
	 */
	public function setDir($dir)
	{
		$this->dir = "{$this->getRoot()}/{$dir}";
		return $this;
	}

	/**
	 * Download remote assets.
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function download()
	{
		// Allow CDN by default
		$cdn = true;

		// Get from remote
		if ( $this->remote ) {
			$api = new API('GET',[
				'timeout'     => 30,
				'redirection' => 1
			]);
			$api->send($this->remote);
			if ( $api->getStatusCode() == 200 ) {
				$archive = "{$this->dir}/{$this->getFileName($this->remote)}";
				if ( File::w($archive,$api->getBody()) ) {
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
			$this->downloadCDN();
		}
	}

	/**
	 * Download CDN assets.
	 *
	 * @access private
	 * @param void
	 * @return void
	 */
	private function downloadCDN()
	{
		$api = new API('GET',[
			'timeout'     => 10,
			'redirection' => 1
		]);
		foreach ($this->assets as $asset => $files) {
			foreach ($files as $file) {
				$filename = "{$this->dir}/{$asset}/{$this->getFileName($file)}";
				if ( !$this->check($filename) ) {
					$api->send("{$this->cdn}/{$file}");
					if ( $api->getStatusCode() == 200 ) {
						File::addDir("{$this->dir}/{$asset}");
						File::w($filename,$api->getBody());
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
	private function extract($archive)
	{
		if ( Archive::uncompress($archive,$this->dir) ) {
			File::remove("{$this->dir}/assets.zip");
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
	private function check($path = false)
	{
		// Check for single asset
		if ( TypeCheck::isString($path) && !empty($path) ) {
			return File::exists($path);
		}
		// Check for all assets
		foreach ($this->getAssets() as $asset => $files) {
			foreach ($files as $file) {
				if ( !File::exists("{$this->dir}/{$asset}/{$file}") ) {
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
	 * @param void
	 * @return void
	 */
	private function reset()
	{
		// Secured removing
		foreach ($this->getAssets() as $asset => $files) {
			$dir = "{$this->dir}/{$asset}";
			if ( File::isDir($dir) && Stringify::contains($dir,"/{$this->getNameSpace()}/") ) {
				File::clearDir($dir);
				File::removeDir($dir);
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
		return basename($path);
	}

	/**
	 * Get assets.
	 *
	 * @access private
	 * @param void
	 * @return array
	 */
	private function getAssets()
	{
		$wrapper = [];
		foreach ($this->assets as $asset => $files) {
			$wrapper[$asset] = Arrayify::map(function($file){
				return $this->getFileName($file);
			}, $files);
		}
		return $wrapper;
	}
}
