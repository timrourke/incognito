<?php

declare(strict_types=1);

namespace Incognito\CognitoClient;

use Aws\Result;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient as CognitoClient;
use Incognito\Entity\User;
use Incognito\Entity\UserAttribute\UserAttribute;

class UserAuthenticationService
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
     * @param \Incognito\Entity\User $user
     * @param string $password
     * @return \Aws\Result
     */
    public function signUpUser(User $user, string $password): Result
    {
        return $this->cognitoClient->signUp([
            'ClientId'   => $this->cognitoCredentials->getClientId(),
            'Password'   => $password,
            'SecretHash' => $this->cognitoCredentials->getSecretHashForUsername(
                $user->username()
            ),
            'UserAttributes' => array_map(
                function(UserAttribute $attr) {
                    return [
                        'Name' => $attr->name(),
                        'Value' => $attr->value(),
                    ];
                },
                $user->getAttributes()
            ),
            'Username' => $user->username(),
        ]);
    }
}
