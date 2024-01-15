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

namespace VanillePlugin\lib;

use VanillePlugin\int\LoggerInterface;

/**
 * Plugin logger.
 */
class Logger implements LoggerInterface
{
	use \VanillePlugin\VanillePluginConfig;

    /**
     * @access private
     * @var string $path
     * @var string $filename
     * @var string $extension
     */
    private $path;
    private $filename;
    private $extension;

    /**
     * @inheritdoc
     */
    public function __construct(?string $path = '/', string $file = 'debug', string $ext = 'log')
    {
		// Init plugin config
		$this->initConfig();
        
        $this->setPath($this->getLoggerPath($path));
        $this->setFilename($file);
        $this->setExtension($ext);

        // Reset plugin config
        $this->resetConfig();
    }

    /**
     * @inheritdoc
     */
    public function setPath(string $path) : self
    {
        $this->path = $path;
        if ( !$this->isDir($this->path) ) {
            $this->addDir($this->path);
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setFilename(string $filename) : self
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setExtension(string $extension) : self
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function debug($message, bool $isArray = false) : bool
    {
        if ( $isArray ) {
            $message = print_r($message, true);
        }
        return $this->write('debug', $message);
    }

    /**
     * @inheritdoc
     */
    public function error(string $message) : bool
    {
        return $this->write('error', $message);
    }

    /**
     * @inheritdoc
     */
    public function warning(string $message) : bool
    {
        return $this->write('warning', $message);
    }

    /**
     * @inheritdoc
     */
    public function info(string $message) : bool
    {
        return $this->write('info', $message);
    }

    /**
     * @inheritdoc
     */
    public function custom(string $message, string $type = 'custom') : bool
    {
        return $this->write($type, $message);
    }

    /**
     * @inheritdoc
     */
    public static function log(string $message, int $type = 0, ?string $path = null, ?string $headers = null) : bool
    {
        return error_log($message, $type, $path, $headers);
    }

    /**
     * Write message.
     *
     * @access protected
     * @param string $status 
     * @param string $message 
     * @return bool
     */
    protected function write(string $status, string $message) : bool
    {
        $date = date('[d-m-Y]');
        $log  = "{$this->path}/{$this->filename}-{$date}.{$this->extension}";
        $date = date('[d-m-Y H:i:s]');
        $msg  = "{$date} : [{$status}] - {$message}" . PHP_EOL;
        return $this->writeFile($log, $msg, true);
    }
}
