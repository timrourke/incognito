<?php

declare(strict_types=1);

namespace Incognito\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * Class CacheItemFactoryStub
 * @package Incognito\Cache
 */
class CacheItemFactoryStub implements CacheItemFactoryInterface
{
    /**
     * @param string $key
     * @param mixed $data
     * @param bool $isHit
     * @return CacheItemInterface
     */
    public function make(string $key, $data, bool $isHit = false): CacheItemInterface
    {
        return new PsrCacheItemStub($key, $data, $isHit);
    }
}