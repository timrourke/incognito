<?php

namespace Incognito\Cache;

class CacheItemFactoryStubTest
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