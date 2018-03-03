<?php // @codeCoverageIgnoreStart

declare(strict_types=1);

namespace Incognito\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * Interface CacheItemFactoryInterface
 *
 * Implement this interface in your application to create a concrete implementation
 * of \Psr\Cache\CacheItemInterface. Useful for ensuring framework interoperability.
 *
 * @package Incognito\Cache
 */
interface CacheItemFactoryInterface
{
    /**
     * Make a concrete implementation of a \Psr\Cache\CacheItemInterface
     *
     * @param string $key The CacheItem's key
     * @param mixed $data The data to cache
     * @param bool $isHit Whether the CacheItem resulted from a cache hit
     * @return \Psr\Cache\CacheItemInterface
     */
    public function make(string $key, $data, bool $isHit): CacheItemInterface;
}
