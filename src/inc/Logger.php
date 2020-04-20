<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Logger
{
    /**
     * @param string $tag
     * @param string $message
     * @return void
     */
    public static function debug($message)
    {
        self::write('DEBUG', $message);
    }

    /**
     * @param string $tag
     * @param string $message
     * @return void
     */
    public static function error($message)
    {
        self::write('ERROR', $message);
    }

    /**
     * @param string $tag
     * @param string $message
     * @return void
     */
    public static function warning($message)
    {
        self::write('WARNING', $message);
    }

    /**
     * @param string $tag
     * @param string $message
     * @return void
     */
    public static function info($message)
    {
        self::write('INFO', $message);
    }

    /**
     * @param string $tag,$message 
     * @return void
     */
    private static function write($status, $message)
    {
        $config = new Config();
        $file = "{$config->root}/core/storage/logs/debug.log";
        $date = date('[d-m-Y H:i:s]');
        $msg = "{$date} : [{$status}] - {$message}" . PHP_EOL;
        file_put_contents($file, $msg, FILE_APPEND);
    }
}
