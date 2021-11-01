<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.4
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\File;

class Logger extends PluginOptions
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

        // Check logger path
        if ( !File::exists($this->getLoggerPath()) ) {
            File::addDir($this->getLoggerPath());
        }
        
        $this->setPath($this->getLoggerPath());
        $this->setFilename('debug');
        $this->setExtension('log');
    }

    /**
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
     * @access public
     * @param string $filename
     * @return void
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @access public
     * @param string $extension
     * @return void
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @access public
     * @param string $message
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
     * @access public
     * @param string $message
     * @return void
     */
    public function error($message = '')
    {
        $this->write('error',$message);
    }

    /**
     * @access public
     * @param string $message
     * @return void
     */
    public function warning($message = '')
    {
        $this->write('warning',$message);
    }

    /**
     * @access public
     * @param string $message
     * @return void
     */
    public function info($message = '')
    {
        $this->write('info',$message);
    }

    /**
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
     * @access protected
     * @param string $status 
     * @param string $message 
     * @return bool
     */
    protected function write($status, $message)
    {
        $date = date('[d-m-Y]');
        $log  = "{$this->path}/{$this->filename}-{$date}.{$this->extension}";
        $date = date('[d-m-Y H:i:s]');
        $msg  = "{$date} : [{$status}] - {$message}" . PHP_EOL;
        return File::w($log,$msg,true);
    }
}
