<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

/**
 * Plugin hash manager.
 */
final class Hasher extends Cache
{
    /**
     * @access private
     */
    private const SECRET = '8Srs';
    private const SALT   = 'l3qw';
    private const TTL    = 86400;

    /**
     * @access private
     * @var string $secret, Hash secret
     * @var string $salt, Hash salt
     * @var int $ttl, Hash TTL
     */
    private $secret;
    private $salt;
    private $ttl;

    public function __construct(string $secret = self::SECRET, string $salt = self::SALT, int $ttl = self::TTL)
    {
        $this->secret = $secret;
        $this->salt   = $salt;
        $this->ttl    = $ttl;
    }

    /**
     * Hash data.
     *
     * @access public
     * @param mixed $data
     * @return mixed
     */
    public function hash($data = null)
    {
        if (!$this->hasInternalCache()) {
            return $data;
        }

        $key = $this->generateHash($data, $this->salt);
        $cache = $this->getInternalCache();
        if (!$cache->has($key)) {
            $data = $this->encrypt($data, $this->secret, 'data:');
            if (!$cache->set($key, $data, $this->ttl)) {
                return $data;
            }
        }

        return "hash:{$key}";
    }

    /**
     * Unhash data.
     *
     * @access public
     * @param mixed $data
     * @return mixed
     */
    public function unhash($data = null)
    {
        if (!$this->hasInternalCache()) {
            return $data;
        }

        if ($this->hasString((string)$data, 'hash:')) {

            $key = $this->generateHash($data, $this->salt);
            $key = $this->removeString('token:', $data);

            $cache = $this->getInternalCache();
            $temp  = $cache->get($key);

            if ($cache->has($key)) {
                $data = $temp;
            }
        }

        return $this->decrypt($data, $this->secret, 'data:');
    }
}
