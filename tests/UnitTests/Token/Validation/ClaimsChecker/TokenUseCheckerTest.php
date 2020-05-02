<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Token\Validation\ClaimsChecker;

use Incognito\Token\Validation\ClaimsChecker\TokenUseChecker;
use Jose\Component\Checker\InvalidClaimException;
use PHPUnit\Framework\TestCase;

class TokenUseCheckerTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new TokenUseChecker();

        static::assertInstanceOf(
            TokenUseChecker::class,
            $sut
        );
    }

    /**
     * @throws \Jose\Component\Checker\InvalidClaimException
     */
    public function testCheckClaim(): void
    {
        $sut = new TokenUseChecker();

        static::assertTrue($sut->checkClaim('id'));
        static::assertTrue($sut->checkClaim('access'));
    }

    public function testCheckClaimThrowsWhenEmpty(): void
    {
        static::expectException(InvalidClaimException::class);
        static::expectExceptionMessage('Invalid claim "token_use". The claim "token_use" must have a value.');

        $sut = new TokenUseChecker();

        $sut->checkClaim('');
    }

    public function testCheckClaimThrowsWhenNotString(): void
    {
        static::expectException(InvalidClaimException::class);
        static::expectExceptionMessage('Invalid claim "token_use". The claim "token_use" must be a string.');

        $sut = new TokenUseChecker();

        $sut->checkClaim(6);
    }

    public function testCheckClaimThrowsWhenNotIdOrAccess(): void
    {
        static::expectException(InvalidClaimException::class);
        static::expectExceptionMessage('Invalid claim "token_use". The claim "token_use" must be "access" or "id".');

        $sut = new TokenUseChecker();

        $sut->checkClaim('some-incorrect-value');
    }

    public function testSupportedClaim(): void
    {
        $sut = new TokenUseChecker();

        static::assertEquals(
            'token_use',
            $sut->supportedClaim()
        );
    }
}
