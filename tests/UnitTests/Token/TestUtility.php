<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Token;

use Jose\Component\Core\Converter\StandardConverter;
use Jose\Component\Core\JWK;
use Jose\Component\Core\JWKSet;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\JWS;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Checker\AlgorithmChecker;
use Jose\Component\Checker\AudienceChecker;
use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker\ExpirationTimeChecker;
use Jose\Component\Checker\HeaderCheckerManager;
use Jose\Component\Checker\IssuedAtChecker;
use Jose\Component\Checker\NotBeforeChecker;
use Jose\Component\Signature\JWSTokenSupport;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManager;

/**
 * Class TestUtility
 *
 * This class provides some helper test factories for creating the dependencies
 * for Cognito services
 *
 * @package Incognito\Token
 */
class TestUtility
{
    /**
     * @var string
     */
    const EXPECTED_AUDIENCE = 'https://some-expected-audience.com';

    /**
     * @var string
     */
    const EXPECTED_ISSUER = 'some issuer';

    /**
     * @var string
     */
    const EXPECTED_KEY_ID = 'expected-key-id';

    /**
     * Create an RSA256 JWKSet
     *
     * @return JWKSet
     */
    public static function getKeyset(): JWKSet
    {
        return JWKSet::createFromKeyData(self::getRsaKeysetData());
    }

    /**
     * Get a public RSA keyset
     *
     * @return array
     */
    public static function getRsaKeysetData(): array
    {
        return [
            "keys" => [
                0 => [
                    "kty" => "RSA",
                    "e" => "AQAB",
                    "use" => "sig",
                    "kid" => "1",
                    "alg" => "RS256",
                    "n" => "uDd-60wgzwASrHGCfg7VsGjrv16ROILTU8nBe4bFJEOLzeEAyaAp8eWpDkoD-HgBl3BU6DC_FUjhdBrUAdrW4yg_oIDQel0uOsc3VwwbbazAKaepvcq-JZhlJuV8NCQYsasYYApke-JFN_3qPXzX8QP5VYFso_oTEaRAfp1jW24ZxLl2EELGXlHr2SNaEOvX1dTCIN_xpONeKlINkUqJfuBi8GH0SjKrLM59TWwKyjmL4GpcT1GfEe_u4LAuXRvd5ZTzQWKe5EeWBaHQ88nSoXN35iGkLPGpBoSD_rNHXXg1j9Gm95SNJIxUbJSTTwFLElBeUn1wnee_l4aas7lidCxgza0kyHREA18xmIJ2XB2DiFZSpR_GmDCXBW8AiwXrDZawxUUPyiEW0NTjB12sd4PqNa3mLwHW0zXj5Jqy6_kQ18xOX_cIXRmepWxwtC_U-r1r_XL7_xoscZaZNiibVs6rlSqsK-aNNm9enUOOdL_-OsYQSOK2B8XajcoCqKKZ8XfPM0_piYzSMO7veJzUQdyUKQqR5SgI3uErmaEEGsFI9Az5RUM6haer_iSUQeBJ7AlGOuxCh49HgaTgF1J1ghrWKzkSUJkN5gaUgDSK4ZQ7j7p1nznBsBlU7ia7JJssZXgJlxiuqoq29_v7lL_vgbXk9uYxKCPnQHigCvyBwuc"
                ]
            ],
        ];
    }

    /**
     * Create a JWSVerifier
     *
     * @return JWSVerifier
     */
    public static function getJwsVerifier(): JWSVerifier
    {
        return new JWSVerifier(self::getAlgorithmManager());
    }

    /**
     * Create a JWS token object
     *
     * @param array $headers
     * @param array $claims
     * @return JWS
     */
    public static function getJWS(array $headers = null, array $claims = null): JWS
    {
        $jsonConverter = new StandardConverter();

        $payload = $jsonConverter->encode($claims ?? self::getValidClaims());

        $builder = new JWSBuilder(
            $jsonConverter,
            self::getAlgorithmManager()
        );

        $token = $builder->withPayload($payload)
            ->addSignature(
                self::getJWK(),
                $headers ?? self::getValidHeaderClaims(),
                [
                ]
            )
            ->build();

        return $token;
    }

    public static function getValidClaims(): array
    {
        return [
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + 3600,
            'iss' => self::EXPECTED_ISSUER,
            'aud' => self::EXPECTED_AUDIENCE,
            'token_use' => 'access', // 'id' would also be valid
        ];
    }

    public static function getValidHeaderClaims(): array
    {
        return [
            'alg' => 'RS256',
            'kid' => self::EXPECTED_KEY_ID,
        ];
    }

    /**
     * Create an AlgorithmManager
     *
     * @return AlgorithmManager
     */
    public static function getAlgorithmManager(): AlgorithmManager
    {
        $rsa256Alg = new RS256();

        return AlgorithmManager::create([$rsa256Alg]);
    }

    /**
     * Create a JWK
     *
     * @return JWK
     */
    public static function getJWK(): JWK
    {
        return JWK::create([
            "kty" => "RSA",
            "d"   => "QA6kJqPpQLHQzIAIFVeJPPevCnOS4ei0HY2ppw-dG9gVFDkqcfIsw73NhHd-W_c27ncUP008FOWr6BiwIhj74i-LuH8yf2pJegzuFGnUW9XXWGTB5IpMhnwwqE7iWqs2nwlUx4i1mlJ7KVY1Dr6LxHZSTEZeBcMewGKyPpTyCMgHU939zPe7TO8FqRLe7cVk3ylFe_MDbwfVJbg0mZImr6TNIrOdI_Psi_8dmT95nQpXtmilwrsqKi2Zji4VYTEgfwXFdLYbdFvkMf8rzqoegR_K0I5gLXS3Btorn_StWzZCwcjwW2Ufk1bswHkFpFJZzXt-4QZXk8ETYVhgESDkt9O_hzQwS2UF9f5GzRs4oDwHnwOK90MC5KO0iMtxAH5r3N6DSKp-0jUqiOlskUDHksL7rERHWI58DR_kMYsCOf_apI88WfhrcL-4cR64Baub5hFOE5GdYR3NMImxgl4R6xvcxpNbLT0gFZkg3cq6YvFU74qRJncJqCztiO4JG4JxZSttq1G66PDaSkZMiieizRBL8y8L60lm5qIkpgmu68ZwhqXXgD6NFpqKWcBAxXrZqt3ctlimap80RQqHuFlrAkYmSs0mGF9s4QJSJeZCkuR7CCJJDMZPcrYnRR8xdW4D0_aHd8pEF88WA4_U55LOM6tztZab-o1XYK8tZtYFYiE",
            "e"   => "AQAB",
            "use" => "sig",
            "kid" => "1",
            "alg" => "RS256",
            "n"   => "uDd-60wgzwASrHGCfg7VsGjrv16ROILTU8nBe4bFJEOLzeEAyaAp8eWpDkoD-HgBl3BU6DC_FUjhdBrUAdrW4yg_oIDQel0uOsc3VwwbbazAKaepvcq-JZhlJuV8NCQYsasYYApke-JFN_3qPXzX8QP5VYFso_oTEaRAfp1jW24ZxLl2EELGXlHr2SNaEOvX1dTCIN_xpONeKlINkUqJfuBi8GH0SjKrLM59TWwKyjmL4GpcT1GfEe_u4LAuXRvd5ZTzQWKe5EeWBaHQ88nSoXN35iGkLPGpBoSD_rNHXXg1j9Gm95SNJIxUbJSTTwFLElBeUn1wnee_l4aas7lidCxgza0kyHREA18xmIJ2XB2DiFZSpR_GmDCXBW8AiwXrDZawxUUPyiEW0NTjB12sd4PqNa3mLwHW0zXj5Jqy6_kQ18xOX_cIXRmepWxwtC_U-r1r_XL7_xoscZaZNiibVs6rlSqsK-aNNm9enUOOdL_-OsYQSOK2B8XajcoCqKKZ8XfPM0_piYzSMO7veJzUQdyUKQqR5SgI3uErmaEEGsFI9Az5RUM6haer_iSUQeBJ7AlGOuxCh49HgaTgF1J1ghrWKzkSUJkN5gaUgDSK4ZQ7j7p1nznBsBlU7ia7JJssZXgJlxiuqoq29_v7lL_vgbXk9uYxKCPnQHigCvyBwuc"
        ]);
    }

    /**
     * Get a ClaimCheckerManager
     *
     * @return ClaimCheckerManager
     */
    public static function getClaimChecker(): ClaimCheckerManager
    {
        return ClaimCheckerManager::create([
            new IssuedAtChecker(),
            new NotBeforeChecker(),
            new ExpirationTimeChecker(),
            new AudienceChecker(self::EXPECTED_AUDIENCE),
        ]);
    }

    /**
     * Get a HeaderCheckerManager
     *
     * @return HeaderCheckerManager
     */
    public static function getHeaderChecker(): HeaderCheckerManager
    {
        return HeaderCheckerManager::create(
            [
                new AlgorithmChecker(['RS256']),
            ],
            [
                new JWSTokenSupport(),
            ]
        );
    }

    /**
     * Get a StandardConverter
     *
     * @return StandardConverter
     */
    public static function getTokenConverter(): StandardConverter
    {
        return new StandardConverter();
    }

    /**
     * Get a serializer manager
     *
     * @return JWSSerializerManager
     */
    public static function getSerializerManager(): JWSSerializerManager
    {
        $serializer = new CompactSerializer(new StandardConverter());

        return JWSSerializerManager::create([$serializer]);
    }
}