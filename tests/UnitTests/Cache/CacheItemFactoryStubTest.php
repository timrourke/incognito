<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Cache;

use PHPUnit\Framework\TestCase;

class CacheItemFactoryStubTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new CacheItemFactoryStub();

        $this->assertInstanceOf(
            CacheItemFactoryStub::class,
            $sut
        );
    }

    public function testMake(): void
    {
        $sut = new CacheItemFactoryStub();

        $this->assertInstanceOf(
            PsrCacheItemStub::class,
            $sut->make('foo', [], true)
        );
    }
}
