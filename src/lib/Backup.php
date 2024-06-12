<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.2
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

/**
 * Plugin backups manager.
 */
final class Backup extends Orm
{
	use \VanillePlugin\tr\TraitSecurable,
		\VanillePlugin\tr\TraitDatable;

	/**
	 * @access private
	 * @var array $tables
	 * @var array $options
	 */
	private $tables = [];
	private $options = [];

    /**
	 * Init backup.
     */
	public function __construct()
	{
		// Init orm
		parent::__construct();
	}

	/**
	 * Set backup tables.
	 * 
	 * @access public
	 * @param array $tables
	 * @return void
	 */
	public function setTables(array $tables)
	{
		$this->tables = $tables;
	}

	/**
	 * Set backup options.
	 * 
	 * @access public
	 * @param array $options
	 * @return void
	 */
	public function setOptions(array $options)
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
	public function export(bool $asFile = false)
	{
		// Init options
		if ( $this->options ) {
			foreach ($this->options as $key => $type) {
				$temp  = $this->applyNamespace($key);
				$value = $this->getOption($temp, $type);
				$wrapper['options'][$key] = $value;
			}
		}

		// Init tables
		if ( $this->tables ) {
			foreach ($this->tables as $table) {
				if ( $this->hasTable($table) ) {
					$wrapper['tables'][$table] = $this->all($table);
				}
			}
		}

		// Encrypt backup
		$encrypt = $this->getCryptor($wrapper);
		$prefix  = $this->applyNameSpace('backup');
		$encrypt->setPrefix($prefix);
		$backup = $encrypt->encrypt();

		if ( $asFile ) {
			$date = $this->getDate('now', 'd-m-Y');
			$file = $this->applyNameSpace("backup-{$date}");
			$file = $this->getTempPath($file);
			return $this->writeFile($file, $backup);
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
	public function import(string $backup, bool $isFile = false) : bool
	{
		$count = 0;
		if ( $isFile ) {
			$file = "{$this->getTempPath()}/{$backup}";
			if ( $this->isFile($file) ) {
				$backup = $this->readFile($file);
			}
		}

		if ( !empty($backup) ) {

			$encrypt = $this->getCryptor($backup);
			$prefix  = $this->applyNameSpace('backup');
			$encrypt->setPrefix($prefix);

			if ( ($backup = $encrypt->decrypt()) ) {

				// Backup options
				if ( isset($backup['options']) ) {
					foreach ($backup['options'] as $key => $value) {
						$key = $this->applyNamespace($key);
						$count += (int)$this->updateOption($key, $value);
					}
				}

				// Backup tables
				if ( isset($backup['tables']) ) {
					foreach ($backup['tables'] as $table => $value) {
						$this->clear($table);
						foreach ($value as $entry) {
							$count += (int)$this->create($table, $entry);
						}
					}
				}
			}
		}

		return (bool)$count;
	}
}
