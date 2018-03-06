<?php

declare(strict_types=1);

namespace Incognito\CognitoClient;

use Aws\Result;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient as CognitoClient;

class UserAuthentication
{
    /**
     * @var \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient
     */
    private $cognitoClient;

    /**
     * @var \Incognito\CognitoClient\CognitoCredentials
     */
    private $cognitoCredentials;

    /**
     * Constructor.
     *
     * @param \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient $cognitoClient
     * @param \Incognito\CognitoClient\CognitoCredentials $cognitoCredentials
     */
    public function __construct(
        CognitoClient $cognitoClient,
        CognitoCredentials $cognitoCredentials
    ) {
        $this->cognitoClient      = $cognitoClient;
        $this->cognitoCredentials = $cognitoCredentials;
    }

    /**
     * Log a user in via username and password
     *
     * @param string $username
     * @param string $password
     * @return \Aws\Result
     */
    public function loginUser(string $username, string $password): Result
    {
        $result = $this->cognitoClient->adminInitiateAuth([
            'AuthFlow'       => 'ADMIN_NO_SRP_AUTH',
            'ClientId'       => $this->cognitoCredentials->getClientId(),
            'UserPoolId'     => $this->cognitoCredentials->getUserPoolId(),
            'AuthParameters' => [
                'SECRET_HASH' => $this->cognitoCredentials->getSecretHashForUsername(
                    $username
                ),
                'USERNAME'    => $username,
                'PASSWORD'    => $password,
            ],
        ]);

        return $result;
    }

    /**
     * Sign up a new user
     *
     * @param array $userAttributes A hash of data to create a new User with
     * @return \Aws\Result
     */
    public function signUpUser(array $userAttributes): Result
    {
        return $this->cognitoClient->signUp([
            'ClientId'   => $this->cognitoCredentials->getClientId(),
            'Password'   => $userAttributes['password'],
            'SecretHash' => $this->cognitoCredentials->getSecretHashForUsername(
                $userAttributes['username']
            ),
            'UserAttributes' => [
                [
                    'Name' => 'given_name',
                    'Value' => trim($userAttributes['first-name']),
                ],
                [
                    'Name' => 'family_name',
                    'Value' => trim($userAttributes['last-name']),
                ],
                [
                    'Name' => 'email',
                    'Value' => trim($userAttributes['email']),
                ],
                [
                    'Name' => 'locale',
                    'Value' => trim($userAttributes['locale']),
                ],
                [
                    'Name' => 'zoneinfo',
                    'Value' => trim($userAttributes['zoneinfo']),
                ],
            ],
            'Username' => $userAttributes['username'],
        ]);
    }
}
