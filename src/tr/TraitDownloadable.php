<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.0
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\lib\Asset;

trait TraitDownloadable
{
	/**
     * Check whether plugin has asset.
     * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasAsset() : bool
	{
		return (new Asset())->hasAsset();
	}

	/**
     * Download plugin remote assets.
     * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function download(string $url)
	{
		(new Asset())->setRemote($url)->download();
	}
}
