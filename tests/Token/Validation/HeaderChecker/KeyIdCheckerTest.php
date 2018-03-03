<?php

declare(strict_types=1);

namespace Incognito\Token\Validation\HeaderChecker;

use PHPUnit\Framework\TestCase;

class KeyIdCheckerTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new KeyIdChecker();

        $this->assertInstanceOf(
            KeyIdChecker::class,
            $sut
        );
    }

    public function testCheckHeader(): void
    {
        $sut = new KeyIdChecker();

        $result = $sut->checkHeader('some-key-id-string');

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Jose\Component\Checker\InvalidHeaderException
     * @expectedExceptionMessage Invalid header "kid". "kid" must have a value.
     */
    public function testCheckHeaderThrowsWhenEmpty(): void
    {
        $sut = new KeyIdChecker();

        $sut->checkHeader('');
    }

    /**
     * @expectedException \Jose\Component\Checker\InvalidHeaderException
     * @expectedExceptionMessage Invalid header "kid". "kid" must be a string.
     */
    public function testCheckHeaderThrowsWhenNotString(): void
    {
        $sut = new KeyIdChecker();

        $sut->checkHeader(82355273);
    }

    public function testSupportedHeader(): void
    {
        $sut = new KeyIdChecker();

        $this->assertEquals(
            'kid',
            $sut->supportedHeader()
        );
    }

    public function testProtectedHeaderOnly(): void
    {
        $sut = new KeyIdChecker();

        $this->assertTrue($sut->protectedHeaderOnly());
    }
}