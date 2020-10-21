<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.2.4
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\int;

interface VanilleCacheInterface
{
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
    public function deleteByTag($tag);

    /**
     * @param void
     * @return boolean
     */
    function isCached();

    /**
     * @param int $expire
     * @return void
     */
    function expireIn($expire = self::EXPIRE);

    /**
     * @param string $path
     * @return void
     */
    function setPath($path);

    /**
     * @param void
     * @return void
     */
    function remove();

    /**
     * @param void
     * @return void
     */
    function removeAll();

    /**
     * @param void
     * @return void
     */
    function removeThirdParty();
}
