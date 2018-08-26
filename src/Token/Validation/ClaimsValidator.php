<?php

declare(strict_types=1);

namespace Incognito\Token\Validation;

use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker\HeaderCheckerManager;
use Jose\Component\Checker\InvalidClaimException;
use Jose\Component\Checker\InvalidHeaderException;
use Jose\Component\Core\Converter\JsonConverter;
use Jose\Component\Signature\JWS;
use Jose\Component\Signature\JWSTokenSupport;

class ClaimsValidator
{
    /**
     * @var array
     */
    private const REQUIRED_HEADER_CLAIMS = [
        'alg',
        'kid',
    ];

    /**
     * @var array
     */
    private const REQUIRED_PAYLOAD_CLAIMS = [
        'token_use',
    ];

    /**
     * @var \Jose\Component\Checker\ClaimCheckerManager
     */
    private $claimChecker;

    /**
     * @var \Jose\Component\Checker\HeaderCheckerManager
     */
    private $headerChecker;

    /**
     * @var \Jose\Component\Core\Converter\JsonConverter
     */
    private $tokenConverter;

    /**
     * @var \Jose\Component\Signature\JWSTokenSupport
     */
    private $tokenSupport;

    /**
     * Constructor.
     *
     * @param \Jose\Component\Checker\ClaimCheckerManager $claimChecker
     * @param \Jose\Component\Checker\HeaderCheckerManager $headerChecker
     * @param \Jose\Component\Core\Converter\JsonConverter $tokenConverter
     * @param \Jose\Component\Signature\JWSTokenSupport $tokenSupport
     */
    public function __construct(
        ClaimCheckerManager $claimChecker,
        HeaderCheckerManager $headerChecker,
        JsonConverter $tokenConverter,
        JWSTokenSupport $tokenSupport
    ) {
        $this->claimChecker = $claimChecker;
        $this->headerChecker = $headerChecker;
        $this->tokenConverter = $tokenConverter;
        $this->tokenSupport = $tokenSupport;
    }

    /**
     * Check a token for having valid header values and claims
     *
     * @param \Jose\Component\Signature\JWS $token
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function validate(JWS $token): bool
    {
        $this->verifyRequiredHeaderClaims($token);
        $this->headerChecker->check($token, 0);

        $payload = (string) $token->getPayload();
        $claims = $this->tokenConverter->decode($payload);
        $this->verifyRequiredPayloadClaims($claims);
        $this->claimChecker->check($claims);

        return true;
    }

    /**
     * Verify that all required header claims are present
     *
     * @param \Jose\Component\Signature\JWS $token
     * @throws \Jose\Component\Checker\InvalidHeaderException
     */
    private function verifyRequiredHeaderClaims(JWS $token): void
    {
        $headers = $this->getTokenHeaders($token);

        foreach (self::REQUIRED_HEADER_CLAIMS as $requiredHeaderClaim) {
            if (!array_key_exists($requiredHeaderClaim, $headers)) {
                throw new InvalidHeaderException(
                    sprintf(
                        'The required header claim "%s" is missing from the token.',
                        $requiredHeaderClaim
                    ),
                    $requiredHeaderClaim,
                    null
                );
            }
        }
    }

    /**
     * Verify that all required payload claims are present
     *
     * @param array $claims
     * @throws \Jose\Component\Checker\InvalidClaimException
     */
    private function verifyRequiredPayloadClaims(array $claims): void
    {
        foreach (self::REQUIRED_PAYLOAD_CLAIMS as $requiredPayloadClaim) {
            if (!array_key_exists($requiredPayloadClaim, $claims)) {
                throw new InvalidClaimException(
                    sprintf(
                        'The required payload claim "%s" is missing from the token.',
                        $requiredPayloadClaim
                    ),
                    $requiredPayloadClaim,
                    null
                );
            }
        }
    }

    /**
     * Get the headers from a token.
     *
     * We only intend to validate the protected headers of a token, so we can
     * ignore the unprotected headers here.
     *
     * @param \Jose\Component\Signature\JWS $token
     * @return array
     */
    private function getTokenHeaders(JWS $token): array
    {
        $protected = [];
        $unprotected = [];
        $this->tokenSupport->retrieveTokenHeaders(
            $token,
            0,
            $protected,
            $unprotected
        );

        return $protected;
    }
}
