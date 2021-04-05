<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.2
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\inc\File;
use VanillePlugin\inc\Stringify;
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
	 * Has asset lock
	 *
	 * @access public
	 * @param void
	 * @return bool
	 */
	public function hasAsset()
	{
		return File::exists("{$this->dir}/asset.lock");
	}

	/**
	 * Lock asset
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
	 * Set remote url
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
	 * Set CDN url
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
	 * Set assets dir
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
	 * Download remote assets
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function download()
	{
		$cdn = true;
		if ( $this->remote ) {
			$client = new Request('GET', ['timeout' => 30]);
			if ( ($response = $client->send($this->remote)) ) {
				if ( $response->getStatusCode() == 200 ) {
					$archive = basename($this->remote);
					if ( File::w("{$this->dir}/{$archive}", $response->getBody()) ) {
						if ( $this->extract("{$this->dir}/{$archive}") && $this->check() ) {
							$cdn = false;
							$this->lock();
						}
					}
				}
			}
		}
		if ( $cdn ) {
			$this->downloadCDN();
		}
	}

	/**
	 * Download CDN assets
	 *
	 * @access private
	 * @param void
	 * @return void
	 */
	private function downloadCDN()
	{
		$this->remove();
		$client = new Request('GET', ['timeout' => 30]);
		foreach ($this->assets as $asset => $files) {
			foreach ($files as $file) {
				if ( ($response = $client->send("{$this->cdn}/{$file}")) ) {
					if ( $response->getStatusCode() == 200 ) {
						$file = basename($file);
						File::addDir("{$this->dir}/{$asset}");
						File::w("{$this->dir}/{$asset}/{$file}", $response->getBody());
					}
				}
			}
		}
		if ( $this->check() ) {
			$this->lock();
		}
	}

	/**
	 * Extract remote assets
	 *
	 * @access private
	 * @param string $path
	 * @return bool
	 */
	private function extract($path)
	{
		$zip = new \ZipArchive;
		if ( @$zip->open($path) ) {
			$this->remove();
			@$zip->extractTo($this->dir);
		  	@$zip->close();
		 	@unlink("{$this->dir}/assets.zip");
		} else {
			return false;
		}
		return true;
	}

	/**
	 * Check assets
	 *
	 * @access private
	 * @param void
	 * @return void
	 */
	private function check()
	{
		foreach ($this->getAssets() as $asset => $files) {
			foreach ($files as $file) {
				if ( !File::exists("{$this->dir}/{$asset}/{$file}") ) {
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Reset assets
	 *
	 * @access private
	 * @param void
	 * @return void
	 */
	private function remove()
	{
		// Secured removing
		foreach ($this->getAssets() as $asset => $files) {
			if ( Stringify::contains("{$this->dir}/{$asset}",$this->getRoot()) ) {
				File::clearDir("{$this->dir}/{$asset}");
				File::removeDir("{$this->dir}/{$asset}");
			}
		}
	}

	/**
	 * Get assets
	 *
	 * @access private
	 * @param void
	 * @return array
	 */
	private function getAssets()
	{
		$wrapper = [];
		foreach ($this->assets as $asset => $files) {
			$wrapper[$asset] = array_map(function($files){
				return basename($files);
			}, $files);
		}
		return $wrapper;
	}
}
