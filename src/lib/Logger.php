<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.5.2
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\Exception;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\File;

class Logger extends PluginOptions
{
    /**
     * @param PluginNameSpaceInterface $plugin
     */
    public function __construct(PluginNameSpaceInterface $plugin)
    {
        // Init plugin config
        $this->initConfig($plugin);
    }

    /**
     * Log plugin message
     *
     * @access public
     * @param string $message
     * @param string $type
     * @param array $args
     * @return void
     */
    public function log($message = 'Unknown', $type = 'DEBUG', $args = [])
    {
        $type = Stringify::uppercase($type);
        if ($type == 'PHP') {
            $type = isset($args['type']) ? $args['type'] : 0;
            $path = isset($args['path']) ? $args['path'] : null;
            $headers = isset($args['headers']) ? $args['headers'] : null;
            $exception = new Exception();
            $exception->log("[{$this->getPluginName()}] : {$message}",$type,$path,$headers);
            return;
        }
        $this->write($message, $type);
    }

    /**
     * @access private
     * @param string $status 
     * @param string $message 
     * @return void
     */
    private function write($message, $status)
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
