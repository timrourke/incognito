<?php

declare(strict_types=1);

namespace Incognito\Token;

use Jose\Component\Signature\JWS;

class Service
{
    /**
     * @var \Incognito\Token\ClaimsValidator
     */
    private $claimsValidator;

    /**
     * @var \Incognito\Token\SignatureValidator
     */
    private $signatureValidator;

    /**
     * @var \Incognito\Token\Deserializer
     */
    private $tokenDeserializer;

    /**
     * Constructor.
     *
     * @param \Incognito\Token\ClaimsValidator $claimsValidator
     * @param \Incognito\Token\SignatureValidator $signatureValidator
     * @param \Incognito\Token\Deserializer $tokenDeserializer
     */
    public function __construct(
        ClaimsValidator $claimsValidator,
        SignatureValidator $signatureValidator,
        Deserializer $tokenDeserializer
    ) {
        $this->claimsValidator    = $claimsValidator;
        $this->signatureValidator = $signatureValidator;
        $this->tokenDeserializer  = $tokenDeserializer;
    }

    /**
     * Verify an AWS Cognito JWT
     *
     * @param string $tokenString
     * @return \Jose\Component\Signature\JWS
     * @throws \Exception
     */
    public function verifyToken(string $tokenString): JWS
    {
        $token = $this->tokenDeserializer->getTokenFromString($tokenString);

        $this->claimsValidator->validate($token);
        $this->signatureValidator->validate($token);

        return $token;
    }
}
