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

namespace VanillePlugin\int;

interface VanilleCacheInterface
{
    /**
     * @param PluginNameSpaceInterface $plugin
     * @return void
     */
    function __construct(PluginNameSpaceInterface $plugin);

    /**
     * @param void
     * @return void
     */
    function __destruct();

    /**
     * @param string $key
     * @return mixed
     */
    function get($key);

    /**
     * @param mixed $data
     * @return void
     */
    function set($data);

    /**
     * @param string $key
     * @param mixed $data
     * @return void
     */
    function update($key, $data);

    /**
     * @param string $key
     * @return void
     */
    function delete($key);

    /**
     * @param string $tag
     * @return void
     */
    function deleteByTag($tag);

    /**
     * @param void
     * @return boolean
     */
    function isCached();

    /**
     * @param void
     * @return void
     */
    public function clear();

    /**
     * @param int $ttl 30
     * @return void
     */
    static function expireIn($ttl = 30);
    
    /**
     * @param void
     * @return void
     */
    static function removeThirdParty();
}
