<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.3
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
use VanillePlugin\int\PluginNameSpaceInterface;

final class Migrate extends Orm
{
	/**
	 * Init db object & config object.
	 *
	 * @param PluginNameSpaceInterface $plugin
	 */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
		$this->initConfig($plugin);
		$this->init();
	}

	/**
	 * Create Plugin tables.
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function table()
	{
		if ( !($tables = $this->load()) ) {
			return;
		}
		foreach ($tables as $table) {
			$sql = File::r("{$this->getMigrate()}/{$table}");
			if ( !empty($sql) ) {
				$sql = Stringify::replace('[DBPREFIX]', $this->prefix, $sql);
				$sql = Stringify::replace('[PREFIX]', $this->getPrefix(), $sql);
				$sql = Stringify::replace('[COLLATE]', $this->collate, $sql);
				$this->query($sql);
			}
		}
		$this->lock();
	}

	/**
	 * Upgrade Plugin tables.
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function upgrade()
	{
		if ( !File::exists($file = "{$this->getMigrate()}/upgrade.sql") ) {
			return;
		}
		if ( File::r($file) ) {
			$handle = fopen($file,'r');
			if ( $handle ) {
			    while ( ($line = fgets($handle)) !== false ) {
					$sql = Stringify::replace('[DBPREFIX]', $this->prefix, $line);
					$sql = Stringify::replace('[PREFIX]', $this->getPrefix(), $sql);
			    	if ( Stringify::contains($sql,'ADD') ) {
			    		$column = $this->parseColumn($sql);
			    		$table = $this->parseTable($sql);
			    		if ( !$this->query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}';") ) {
			    			$this->query($sql);
			    		}
			    	} else {
			    		$this->query($sql);
			    	}
			    }
			    fclose($handle);
			}
		}
	}

	/**
	 * Remove Plugin tables.
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function rollback()
	{
		if ( !File::exists($file = "{$this->getMigrate()}/uninstall.sql") ) {
			return;
		}
		if ( !empty(($sql = File::r($file))) ) {
			$sql = Stringify::replace('[DBPREFIX]', $this->prefix, $sql);
			$sql = Stringify::replace('[PREFIX]', $this->getPrefix(), $sql);
			$this->query($sql);
		}
	}

	/**
	 * Check whether plugin has migrate lock.
	 *
	 * @access public
	 * @param void
	 * @return bool
	 */
	public function isMigrated()
	{
		return File::exists("{$this->getMigrate()}/migrate.lock");
	}

	/**
	 * Parse table name for upgrade.
	 *
	 * @access private
	 * @param string $query
	 * @return string
	 */
	private function parseTable($query)
	{
		return Stringify::match('/ALTER\sTABLE\s`(.*)`\sADD/s',$query);
	}

	/**
	 * Parse column name for upgrade.
	 *
	 * @access private
	 * @param string $query
	 * @return string
	 */
	private function parseColumn($query)
	{
		return Stringify::match('/ADD\s`(.*)`\s/s',$query);
	}

	/**
	 * Create migrate lock file.
	 *
	 * @access private
	 * @param void
	 * @return void
	 */
	private function lock()
	{
		File::w("{$this->getMigrate()}/migrate.lock");
	}

	/**
	 * Load SQL files.
	 *
	 * @access private
	 * @param void
	 * @return array
	 */
	private function load()
	{
		return Arrayify::diff(scandir($this->getMigrate()),[
			'.',
			'..',
			'migrate.lock',
			'uninstall.sql',
			'upgrade.sql'
		]);
	}
}
