<?php

declare(strict_types=1);

namespace Incognito\Token;

use Jose\Component\Signature\JWS;
use Jose\Component\Signature\Serializer\JWSSerializerManager;

/**
 * Class Deserializer
 *
 * Deserialize a compact string representation of a JWS into an object
 * representation
 *
 * @package Incognito\Token
 */
class Deserializer
{
    /**
     * @var \Jose\Component\Signature\Serializer\JWSSerializerManager
     */
    private $serializer;

    /**
     * Constructor
     *
     * @param \Jose\Component\Signature\Serializer\JWSSerializerManager $serializer
     */
    public function __construct(JWSSerializerManager $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Get a JWS token object from its string representation
     *
     * @param string $tokenString
     * @return \Jose\Component\Signature\JWS
     * @throws \Exception
     */
    public function getTokenFromString(string $tokenString): JWS
    {
        return $this->serializer->unserialize($tokenString);
    }
}
