<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.5
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\Encryption;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Json;
use VanillePlugin\inc\Date;
use VanillePlugin\int\PluginNameSpaceInterface;

final class Backup extends Orm
{
	/**
	 * @access private
	 * @var array $tables
	 * @var array $options
	 */
	private $tables = [];
	private $options = [];

    /**
     * @param PluginNameSpaceInterface $plugin
     */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
        // Init plugin config
        $this->initConfig($plugin);
		
		// Init plugin db
		$this->init();
	}

	/**
	 * @access public
	 * @param array $tables
	 * @return void
	 */
	public function setTables($tables)
	{
		$this->tables = $tables;
	}

	/**
	 * @access public
	 * @param array $options
	 * @return void
	 */
	public function setOptions($options)
	{
		$this->options = $options;
	}

	/**
	 * Export backup.
	 * 
	 * @access public
	 * @param bool $file
	 * @return mixed
	 */
	public function export($asFile = false)
	{
		// Init options
		if ( $this->options ) {
			foreach ($this->options as $option => $type) {
				$wrapper['options'][$option] = $this->getPluginOption($option, $type);
			}
		}

		// Init tables
		if ( $this->tables ) {
			foreach ($this->tables as $table) {
				if ( $this->hasTable($table) ) {
					$wrapper['tables'][$table] = $this->select(new OrmQuery([
						'table' => $table
					]));
				}
			}
		}

		// Encrypt backup
		$encrypt = new Encryption(Json::encode($wrapper));
		$encrypt->setPrefix("[{$this->getNameSpace()}-backup]");
		$backup = $encrypt->encrypt();
		if ( $asFile ) {
			$date = Date::get('now', 'd-m-Y');
			$filename = "{$this->getTempPath()}/{$this->getNameSpace()}-backup-{$date}";
			if ( File::w($filename, $backup) ) {
				return $filename;
			}
			return false;
		}

		return $backup;
	}

	/**
	 * Import backup.
	 * 
	 * @access public
	 * @param string $backup
	 * @param bool $file
	 * @return bool
	 */
	public function import($backup, $isFile = false)
	{
		$count = 0;
		if ( $isFile ) {
			if ( File::exists("{$this->getTempPath()}/{$backup}") ) {
				$backup = File::r(
					"{$this->getTempPath()}/{$backup}"
				);
			}
		}

		if ( !empty($backup) ) {

			$encrypt = new Encryption($backup);
			$encrypt->setPrefix("[{$this->getNameSpace()}-backup]");

			if ( ($backup = Json::decode($encrypt->decrypt(), true)) ) {

				// Backup options
				if ( isset($backup['options']) ) {
					foreach ($backup['options'] as $option => $value) {
						$count += (int)$this->updatePluginOption($option, $value);
					}
				}

				// Backup tables
				if ( isset($backup['tables']) ) {
					foreach ($backup['tables'] as $table => $value) {
						$this->deleteAll($table);
						foreach ($value as $entry) {
							$count += (int)$this->insert($table, $entry);
						}
					}
				}
			}
		}

		return (bool)$count;
	}
}
