<?php

declare(strict_types=1);

namespace Incognito\CognitoClient;

use Aws\Result;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient as CognitoClient;

class UserQueryService
{
    /**
     * @var \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient
     */
    private CognitoClient $cognitoClient;

    /**
     * @var \Incognito\CognitoClient\CognitoCredentials
     */
    private CognitoCredentials $cognitoCredentials;

    /**
     * UserQueryService constructor.
     *
     * @param \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient $cognitoClient
     * @param \Incognito\CognitoClient\CognitoCredentials                $cognitoCredentials
     */
    public function __construct(
        CognitoClient $cognitoClient,
        CognitoCredentials $cognitoCredentials
    ) {
        $this->cognitoClient      = $cognitoClient;
        $this->cognitoCredentials = $cognitoCredentials;
    }

    /**
     * Get a user by username
     *
     * @param  string $username
     * @return \Aws\Result
     */
    public function getUserByUsername(string $username): Result
    {
        return $this->cognitoClient->adminGetUser([
            'UserPoolId' => $this->cognitoCredentials->getUserPoolId(),
            'Username'   => $username,
        ]);
    }

    /**
     * Get a list of users
     *
     * WARNING: If you have many users, this may be a very large request!
     *
     * TODO: Implement query filters
     *
     * @return \Aws\Result
     */
    public function getList(): Result
    {
        return $this->cognitoClient->listUsers([
            'UserPoolId' => $this->cognitoCredentials->getUserPoolId(),
        ]);
    }
}
