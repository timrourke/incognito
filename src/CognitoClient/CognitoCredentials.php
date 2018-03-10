<?php

declare(strict_types=1);

namespace Incognito\CognitoClient;

/**
 * Class CognitoCredentials
 *
 * A value object to wrap the common credentials for your AWS Cognito User Pool
 *
 * @package Incognito\CognitoClient
 */
class CognitoCredentials
{
    /**
     * The AWS Cognito client ID
     *
     * @var string
     */
    private $clientId;

    /**
     * The AWS Cognito client secret
     *
     * @var string
     */
    private $clientSecret;

    /**
     * The AWS Cognito User Pool ID
     *
     * @var string
     */
    private $userPoolId;

    /**
     * Constructor.
     *
     * @param string $cognitoClientId
     * @param string $cognitoClientSecret
     * @param string $cognitoUserPoolId
     */
    public function __construct(
        string $cognitoClientId,
        string $cognitoClientSecret,
        string $cognitoUserPoolId
    ) {
        $this->clientId     = $cognitoClientId;
        $this->clientSecret = $cognitoClientSecret;
        $this->userPoolId   = $cognitoUserPoolId;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @return string
     */
    public function getUserPoolId(): string
    {
        return $this->userPoolId;
    }

    /**
     * Create a base64-encoded HMAC SHA256 signature to sign the SignUp request
     *
     * @param string $username
     * @return string
     */
    public function getSecretHashForUsername(string $username): string
    {
        $secretHash = hash_hmac(
            'sha256',
            $username . $this->clientId,
            $this->clientSecret,
            true
        );

        return base64_encode($secretHash);
    }
}