<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Token\Validation\HeaderChecker;

use Incognito\Token\Validation\HeaderChecker\KeyIdChecker;
use Jose\Component\Checker\InvalidHeaderException;
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

    /**
     * @throws \Jose\Component\Checker\InvalidHeaderException
     */
    public function testCheckHeader(): void
    {
        $sut = new KeyIdChecker();

        $result = $sut->checkHeader('some-key-id-string');

        $this->assertTrue($result);
    }

    public function testCheckHeaderThrowsWhenEmpty(): void
    {
        $this->expectException(InvalidHeaderException::class);
        $this->expectExceptionMessage(
            'Invalid header "kid". "kid" must have a value.'
        );

        $sut = new KeyIdChecker();

        $sut->checkHeader('');
    }

    public function testCheckHeaderThrowsWhenNotString(): void
    {
        $this->expectException(InvalidHeaderException::class);
        $this->expectExceptionMessage(
            'Invalid header "kid". "kid" must be a string.'
        );

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
