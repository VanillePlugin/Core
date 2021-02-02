<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.5
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use VanillePlugin\lib\PluginOptions;
use VanillePlugin\int\PluginNameSpaceInterface;

class Logger extends PluginOptions
{
    /**
     * @param PluginNameSpaceInterface $plugin
     * @return void
     */
    public function __construct(PluginNameSpaceInterface $plugin)
    {
        // Init plugin config
        $this->initConfig($plugin);
    }

    /**
     * @access public
     * @param string $message
     * @return void
     */
    public function debug($message)
    {
        $this->write('DEBUG', $message);
    }

    /**
     * @access public
     * @param string $message
     * @return void
     */
    public function error($message)
    {
        $this->write('ERROR', $message);
    }

    /**
     * @access public
     * @param string $message
     * @return void
     */
    public function warning($message)
    {
        $this->write('WARNING', $message);
    }

    /**
     * @access public
     * @param string $message
     * @return void
     */
    public function info($message)
    {
        $this->write('INFO', $message);
    }

    /**
     * Log plugin message
     *
     * @access protected
     * @param string $message
     * @param int $type 0
     * @param string $path
     * @param string $headers
     * @return void
     */
    protected function log($message = '', $type = 0, $path = null, $headers = null)
    {
        error_log("{$this->getPluginName()} : {$message}", $type, $path, $headers);
    }

    /**
     * @access private
     * @param string $status 
     * @param string $message 
     * @return void
     */
    private function write($status, $message)
    {
        // Check logger path
        if ( !File::exists($this->getLoggerPath()) ) {
            File::addDir($this->getLoggerPath());
        }
        $date = date('[d-m-Y]');
        $log = "{$this->getLoggerPath()}/debug-{$date}.log";
        $date = date('[d-m-Y H:i:s]');
        $msg = "{$date} : [{$status}] - {$message}" . PHP_EOL;
        File::w($log, $msg, true);
    }
}
