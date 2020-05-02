<?php

declare(strict_types=1);

namespace Incognito\Token;

use Incognito\Token\Validation\ClaimsChecker\TokenUseChecker;
use Incognito\Token\Validation\HeaderChecker\KeyIdChecker;
use Incognito\Token\Validation\ClaimsValidator;
use Incognito\Token\Validation\SignatureValidator;
use Jose\Component\Checker\AlgorithmChecker;
use Jose\Component\Checker\AudienceChecker;
use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker\ExpirationTimeChecker;
use Jose\Component\Checker\HeaderCheckerManager;
use Jose\Component\Checker\IssuedAtChecker;
use Jose\Component\Checker\NotBeforeChecker;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWKSet;
use Jose\Component\Core\Converter\StandardConverter;
use Jose\Component\Signature\JWSTokenSupport;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManager;

/**
 * Class TokenValidatorFactory
 *
 * Builds Cognito token validator service with all of its dependencies
 *
 * @package Incognito\Token
 */
class TokenValidatorFactory
{
    /**
     * Build a Cognito token service with all of its dependencies
     *
     * @param  string $cognitoClientAppId
     * @param  JWKSet $keyset
     * @return TokenValidator
     */
    public static function make(
        string $cognitoClientAppId,
        JWKSet $keyset
    ): TokenValidator {
        return new TokenValidator(
            self::getClaimsValidator($cognitoClientAppId),
            self::getSignatureValidator($keyset),
            self::getDeserializer()
        );
    }

    /**
     * Get a Cognito token claims validator
     *
     * @param  string $cognitoClientAppId
     * @return ClaimsValidator
     */
    private static function getClaimsValidator(
        string $cognitoClientAppId
    ): ClaimsValidator {
        $claimCheckerManager = ClaimCheckerManager::create(
            [
            new IssuedAtChecker(),
            new NotBeforeChecker(),
            new ExpirationTimeChecker(),
            new AudienceChecker($cognitoClientAppId),
            new TokenUseChecker(),
            ]
        );

        $headerCheckerManager = HeaderCheckerManager::create(
            [
                new AlgorithmChecker(['RS256']),
                new KeyIdChecker(),
            ],
            [
                new JWSTokenSupport(),
            ]
        );

        return new ClaimsValidator(
            $claimCheckerManager,
            $headerCheckerManager,
            new JWSTokenSupport()
        );
    }

    /**
     * Get a Cognito token signature validator
     *
     * @param  \Jose\Component\Core\JWKSet $keyset
     * @return \Incognito\Token\Validation\SignatureValidator
     */
    private static function getSignatureValidator(
        JWKSet $keyset
    ): SignatureValidator {
        $rsa256Alg = new RS256();

        $algorithmManager = new AlgorithmManager([$rsa256Alg]);

        $jwsVerifier = new JWSVerifier($algorithmManager);

        return new SignatureValidator(
            $keyset,
            $jwsVerifier
        );
    }

    /**
     * Get a Cognito token deserializer
     *
     * @return \Incognito\Token\Deserializer
     */
    private static function getDeserializer(): Deserializer
    {
        $serializer = new CompactSerializer(new StandardConverter());

        $serializerManager = JWSSerializerManager::create(
            [
            $serializer,
            ]
        );

        return new Deserializer($serializerManager);
    }
}
