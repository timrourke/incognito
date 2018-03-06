<?php

declare(strict_types=1);

namespace Incognito\Token;

use Jose\Component\Signature\JWS;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    /**
     * @var \Jose\Component\Signature\Serializer\JWSSerializerManager
     */
    private $serializer;

    /**
     * @var \Incognito\Token\TokenValidator
     */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        $this->serializer = TestUtility::getSerializerManager();
        $this->sut = TokenValidatorFactory::make(
            TestUtility::EXPECTED_AUDIENCE,
            TestUtility::getKeyset()
        );
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            TokenValidator::class,
            $this->sut
        );
    }

    public function testVerifyToken(): void
    {
        $actual = $this->sut->verifyToken($this->getValidTokenString());

        $this->assertInstanceOf(
            JWS::class,
            $actual
        );
    }

    /**
     * @expectedException \Jose\Component\Checker\InvalidClaimException
     * @expectedExceptionMessage The JWT has expired.
     */
    public function testVerifyTokenThrowsWithExpiredToken(): void
    {
        $this->sut->verifyToken($this->getExpiredTokenString());
    }

    /**
     * @expectedException \Jose\Component\Checker\InvalidClaimException
     * @expectedExceptionMessage The JWT can not be used yet.
     */
    public function testVerifyTokenThrowsWithBeforeNotBefore(): void
    {
        $this->sut->verifyToken($this->getBeforeNotBeforeTokenString());
    }

    /**
     * @expectedException \Jose\Component\Checker\InvalidClaimException
     * @expectedExceptionMessage The JWT is issued in the future.
     */
    public function testVerifyTokenThrowsWithFutureIssuedAt(): void
    {
        $this->sut->verifyToken($this->getFutureIssuedAtTokenString());
    }

    /**
     * @expectedException \Jose\Component\Checker\InvalidClaimException
     * @expectedExceptionMessage Bad audience.
     */
    public function testVerifyTokenThrowsWithInvalidAudience(): void
    {
        $this->sut->verifyToken($this->getInvalidAudienceTokenString());
    }

    /**
     * @expectedException \Jose\Component\Checker\InvalidClaimException
     * @expectedExceptionMessage The required payload claim "token_use" is missing from the token.
     */
    public function testVerifyTokenThrowsWithMissingTokenUseClaim(): void
    {
        $this->sut->verifyToken($this->getMissingTokenUseTokenString());
    }

    /**
     * @expectedException \Jose\Component\Checker\InvalidHeaderException
     * @expectedExceptionMessage The required header claim "kid" is missing from the token.
     */
    public function testVerifyTokenThrowsWithMissingKeyIdHeader(): void
    {
        $this->sut->verifyToken($this->getMissingKeyIdHeadersTokenString());
    }

    private function getValidTokenString(): string
    {
        $token = TestUtility::getJWS();

        return $this->serializeToken($token);
    }

    private function getExpiredTokenString(): string
    {
        $expiredClaims = array_merge(
            TestUtility::getValidClaims(),
            ['exp' => time() - 3600]
        );

        $token = TestUtility::getJWS(null, $expiredClaims);

        return $this->serializeToken($token);
    }

    private function getBeforeNotBeforeTokenString(): string
    {
        $beforeNotBeforeClaims = array_merge(
            TestUtility::getValidClaims(),
            ['nbf' => time() + 3600]
        );

        $token = TestUtility::getJWS(null, $beforeNotBeforeClaims);

        return $this->serializeToken($token);
    }

    private function getFutureIssuedAtTokenString(): string
    {
        $futureIssuedAtClaims = array_merge(
            TestUtility::getValidClaims(),
            ['iat' => time() + 3600]
        );

        $token = TestUtility::getJWS(null, $futureIssuedAtClaims);

        return $this->serializeToken($token);
    }

    private function getInvalidAudienceTokenString(): string
    {
        $invalidAudienceClaims = array_merge(
            TestUtility::getValidClaims(),
            ['aud' => 'https://some-unexpected-audience.com']
        );

        $token = TestUtility::getJWS(null, $invalidAudienceClaims);

        return $this->serializeToken($token);
    }

    private function getMissingKeyIdHeadersTokenString(): string
    {
        $validHeaders = TestUtility::getValidHeaderClaims();

        $headersMissingKeyId = array_filter(
            $validHeaders,
            function($v, $k) {
                return $k !== 'kid';
            },
            ARRAY_FILTER_USE_BOTH
        );

        $token = TestUtility::getJWS($headersMissingKeyId, null);

        return $this->serializeToken($token);
    }

    private function getMissingTokenUseTokenString(): string
    {
        $validClaims = TestUtility::getValidClaims();

        $claimsMissingTokenUse = array_filter(
            $validClaims,
            function($v, $k) {
                return $k !== 'token_use';
            },
            ARRAY_FILTER_USE_BOTH
        );

        $token = TestUtility::getJWS(null, $claimsMissingTokenUse);

        return $this->serializeToken($token);
    }

    private function serializeToken(JWS $token): string
    {
        return $this->serializer->serialize('jws_compact', $token);
    }
}