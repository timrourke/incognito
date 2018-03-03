<?php

declare(strict_types=1);

namespace Incognito\Token\Validation\ClaimsChecker;

use PHPUnit\Framework\TestCase;

class TokenUseCheckerTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new TokenUseChecker();

        $this->assertInstanceOf(
            TokenUseChecker::class,
            $sut
        );
    }

    public function testCheckClaim(): void
    {
        $sut = new TokenUseChecker();

        $this->assertTrue($sut->checkClaim('id'));
        $this->assertTrue($sut->checkClaim('access'));
    }

    /**
     * @expectedException \Jose\Component\Checker\InvalidClaimException
     * @expectedExceptionMessage Invalid claim "token_use". The claim "token_use" must have a value.
     */
    public function testCheckClaimThrowsWhenEmpty(): void
    {
        $sut = new TokenUseChecker();

        $sut->checkClaim('');
    }

    /**
     * @expectedException \Jose\Component\Checker\InvalidClaimException
     * @expectedExceptionMessage Invalid claim "token_use". The claim "token_use" must be a string.
     */
    public function testCheckClaimThrowsWhenNotString(): void
    {
        $sut = new TokenUseChecker();

        $sut->checkClaim(6);
    }

    /**
     * @expectedException \Jose\Component\Checker\InvalidClaimException
     * @expectedExceptionMessage Invalid claim "token_use". The claim "token_use" must be "access" or "id".
     */
    public function testCheckClaimThrowsWhenNotIdOrAccess(): void
    {
        $sut = new TokenUseChecker();

        $sut->checkClaim('some-incorrect-value');
    }

    public function testSupportedClaim(): void
    {
        $sut = new TokenUseChecker();

        $this->assertEquals(
            'token_use',
            $sut->supportedClaim()
        );
    }
}