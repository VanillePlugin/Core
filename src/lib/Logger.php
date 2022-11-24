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

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\int\LoggerInterface;
use VanillePlugin\inc\File;

class Logger extends PluginOptions implements LoggerInterface
{
    /**
     * @access protected
     * @var string $path
     * @var string $filename
     * @var string $extension
     */
    protected $path;
    protected $filename;
    protected $extension;

    /**
     * @param PluginNameSpaceInterface $plugin
     */
    public function __construct(PluginNameSpaceInterface $plugin)
    {
        // Init plugin config
        $this->initConfig($plugin);
        
        // Init logger
        $this->setPath($this->getLoggerPath());
        $this->setFilename('debug');
        $this->setExtension('log');
    }

    /**
     * Get debug status.
     *
     * @access public
     * @param bool $global
     * @return bool
     */
    public function isDebug($global = false)
    {
        return parent::isDebug($global);
    }

    /**
     * Set logger path.
     *
     * @access public
     * @param string $path
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
        if ( !File::isDir($this->path) ) {
            File::addDir($this->path);
        }
    }

    /**
     * Set logger filename.
     *
     * @access public
     * @param string $filename
     * @return void
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Set logger extension.
     *
     * @access public
     * @param string $extension
     * @return void
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Set logger debug.
     *
     * @access public
     * @param string $message
     * @param bool $isArray
     * @return void
     */
    public function debug($message = '', $isArray = false)
    {
        if ( $isArray ) {
            $message = print_r($message,true);
        }
        $this->write('debug',$message);
    }

    /**
     * Set logger error.
     *
     * @access public
     * @param string $message
     * @return void
     */
    public function error($message = '')
    {
        $this->write('error',$message);
    }

    /**
     * Set logger warning.
     *
     * @access public
     * @param string $message
     * @return void
     */
    public function warning($message = '')
    {
        $this->write('warning',$message);
    }

    /**
     * Set logger info.
     *
     * @access public
     * @param string $message
     * @return void
     */
    public function info($message = '')
    {
        $this->write('info',$message);
    }

    /**
     * Set logger custom message.
     *
     * @access public
     * @param string $message
     * @param string $type
     * @return void
     */
    public function custom($message = '', $type = 'custom')
    {
        $this->write($type,$message);
    }

    /**
     * Log natif PHP errors.
     *
     * @access public
     * @param string $message
     * @param int $type 0
     * @param string $path
     * @param string $headers
     * @return void
     */
    public function log($message = '', $type = 0, $path = null, $headers = null)
    {
        error_log($message,$type,$path,$headers);
    }

    /**
     * Write logger message.
     *
     * @access protected
     * @param string $status 
     * @param string $message 
     * @return bool
     */
    protected function write($status, $message)
    {
        if ( File::isDir($this->path) && File::isWritable($this->path) ) {
            $date = date('[d-m-Y]');
            $file = "{$this->path}/{$this->filename}-{$date}.{$this->extension}";
            $date = date('[d-m-Y H:i:s]');
            $msg  = "{$date} : [{$status}] - {$message}" . PHP_EOL;
            return File::w($file,$msg,true);
        }
        return false;
    }
}
