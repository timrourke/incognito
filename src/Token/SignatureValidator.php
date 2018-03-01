<?php

declare(strict_types=1);

namespace Incognito\Token;

use Jose\Component\Core\JWKSet;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\JWS;

class SignatureValidator
{
    /**
     * @var \Jose\Component\Core\JWKSet
     */
    private $keyset;

    /**
     * @var \Jose\Component\Signature\JWSVerifier
     */
    private $tokenVerifier;

    /**
     * Constructor.
     *
     * @param \Jose\Component\Core\JWKSet $keyset
     * @param \Jose\Component\Signature\JWSVerifier $tokenVerifier
     */
    public function __construct(JWKSet $keyset, JWSVerifier $tokenVerifier)
    {
        $this->keyset = $keyset;
        $this->tokenVerifier = $tokenVerifier;
    }

    /**
     * Check if a token's signature is valid
     *
     * @param \Jose\Component\Signature\JWS $token
     * @return bool
     */
    public function validate(JWS $token): bool
    {
        return $this->tokenVerifier->verifyWithKeySet($token, $this->keyset, 0);
    }
}