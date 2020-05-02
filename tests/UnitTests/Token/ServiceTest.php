<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Token;

use Jose\Component\Checker\InvalidClaimException;
use Jose\Component\Checker\InvalidHeaderException;
use Jose\Component\Signature\JWS;
use Incognito\Token\TokenValidator;
use Incognito\Token\TokenValidatorFactory;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    /**
     * @var \Jose\Component\Signature\Serializer\JWSSerializerManager
     */
    private JWSSerializerManager $serializer;

    /**
     * @var \Incognito\Token\TokenValidator
     */
    private TokenValidator $sut;

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
        static::assertInstanceOf(
            TokenValidator::class,
            $this->sut
        );
    }

    /**
     * @throws \Exception
     */
    public function testVerifyToken(): void
    {
        $actual = $this->sut->verifyToken($this->getValidTokenString());

        static::assertInstanceOf(
            JWS::class,
            $actual
        );
    }

    /**
     * @throws \Exception
     */
    public function testVerifyTokenThrowsWithExpiredToken(): void
    {
        static::expectException(InvalidClaimException::class);
        static::expectExceptionMessage('The JWT has expired.');

        $this->sut->verifyToken($this->getExpiredTokenString());
    }

    /**
     * @throws \Exception
     */
    public function testVerifyTokenThrowsWithBeforeNotBefore(): void
    {
        static::expectException(InvalidClaimException::class);
        static::expectExceptionMessage('The JWT can not be used yet.');

        $this->sut->verifyToken($this->getBeforeNotBeforeTokenString());
    }

    /**
     * @throws \Exception
     */
    public function testVerifyTokenThrowsWithFutureIssuedAt(): void
    {
        static::expectException(InvalidClaimException::class);
        static::expectExceptionMessage('The JWT is issued in the future.');

        $this->sut->verifyToken($this->getFutureIssuedAtTokenString());
    }

    /**
     * @throws \Exception
     */
    public function testVerifyTokenThrowsWithInvalidAudience(): void
    {
        static::expectException(InvalidClaimException::class);
        static::expectExceptionMessage('Bad audience.');

        $this->sut->verifyToken($this->getInvalidAudienceTokenString());
    }

    /**
     * @throws \Exception
     */
    public function testVerifyTokenThrowsWithMissingTokenUseClaim(): void
    {
        static::expectException(InvalidClaimException::class);
        static::expectExceptionMessage('The required payload claim "token_use" is missing from the token.');

        $this->sut->verifyToken($this->getMissingTokenUseTokenString());
    }

    /**
     * @throws \Exception
     */
    public function testVerifyTokenThrowsWithMissingKeyIdHeader(): void
    {
        static::expectException(InvalidHeaderException::class);
        static::expectExceptionMessage('The required header claim "kid" is missing from the token.');

        $this->sut->verifyToken($this->getMissingKeyIdHeadersTokenString());
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getValidTokenString(): string
    {
        $token = TestUtility::getJWS();

        return $this->serializeToken($token);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getExpiredTokenString(): string
    {
        $expiredClaims = array_merge(
            TestUtility::getValidClaims(),
            ['exp' => time() - 3600]
        );

        $token = TestUtility::getJWS(null, $expiredClaims);

        return $this->serializeToken($token);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getBeforeNotBeforeTokenString(): string
    {
        $beforeNotBeforeClaims = array_merge(
            TestUtility::getValidClaims(),
            ['nbf' => time() + 3600]
        );

        $token = TestUtility::getJWS(null, $beforeNotBeforeClaims);

        return $this->serializeToken($token);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getFutureIssuedAtTokenString(): string
    {
        $futureIssuedAtClaims = array_merge(
            TestUtility::getValidClaims(),
            ['iat' => time() + 3600]
        );

        $token = TestUtility::getJWS(null, $futureIssuedAtClaims);

        return $this->serializeToken($token);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getInvalidAudienceTokenString(): string
    {
        $invalidAudienceClaims = array_merge(
            TestUtility::getValidClaims(),
            ['aud' => 'https://some-unexpected-audience.com']
        );

        $token = TestUtility::getJWS(null, $invalidAudienceClaims);

        return $this->serializeToken($token);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getMissingKeyIdHeadersTokenString(): string
    {
        $validHeaders = TestUtility::getValidHeaderClaims();

        $headersMissingKeyId = array_filter(
            $validHeaders,
            function (string $k): bool {
                return $k !== 'kid';
            },
            ARRAY_FILTER_USE_KEY
        );

        $token = TestUtility::getJWS($headersMissingKeyId, null);

        return $this->serializeToken($token);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getMissingTokenUseTokenString(): string
    {
        $validClaims = TestUtility::getValidClaims();

        $claimsMissingTokenUse = array_filter(
            $validClaims,
            function (string $k): bool {
                return $k !== 'token_use';
            },
            ARRAY_FILTER_USE_KEY
        );

        $token = TestUtility::getJWS(null, $claimsMissingTokenUse);

        return $this->serializeToken($token);
    }

    /**
     * @param \Jose\Component\Signature\JWS $token
     * @return string
     * @throws \Exception
     */
    private function serializeToken(JWS $token): string
    {
        return $this->serializer->serialize('jws_compact', $token);
    }
}
