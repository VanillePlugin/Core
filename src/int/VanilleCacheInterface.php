<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.8.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

namespace VanillePlugin\int;

interface VanilleCacheInterface
{
    /**
     * @param PluginNameSpaceInterface $plugin
     */
    function __construct(PluginNameSpaceInterface $plugin);
    
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
     * @return bool
     */
    function isCached();

    /**
     * @param void
     * @return void
     */
    public function flush();

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
