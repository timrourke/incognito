<?php

declare(strict_types=1);

namespace Incognito\Repository;

use Incognito\CognitoClient\CognitoCredentials;
use Incognito\Entity\User;
use Incognito\Mapper\UserMapper;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient as CognitoClient;

class UserRepository
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
     * @var \Incognito\Mapper\UserMapper
     */
    private $mapper;

    /**
     * Constructor.
     *
     * @param \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient $cognitoClient
     * @param \Incognito\CognitoClient\CognitoCredentials $cognitoCredentials
     * @param \Incognito\Mapper\UserMapper $mapper
     */
    public function __construct(
        CognitoClient $cognitoClient,
        CognitoCredentials $cognitoCredentials,
        UserMapper $mapper
    )
    {
        $this->cognitoClient = $cognitoClient;
        $this->cognitoCredentials = $cognitoCredentials;
        $this->mapper = $mapper;
    }

    /**
     * Get a User by username
     *
     * @param string $username
     * @return \Incognito\Entity\User
     */
    public function find(string $username): User
    {
        $result = $this->cognitoClient->adminGetUser([
            'UserPoolId' => $this->cognitoCredentials->getUserPoolId(),
            'Username'   => $username,
        ]);

        return $this->mapper->mapAdminGetUserResult($result);
    }

    /**
     * Get all Users in the Cognito User Pool
     *
     * WARNING: If you have many users, this may be a very large request.
     *
     * TODO: Implement query filters
     *
     * @return \Incognito\Entity\User[]
     */
    public function findAll(): array
    {
        $result = $this->cognitoClient->listUsers([
            'UserPoolId' => $this->cognitoCredentials->getUserPoolId(),
        ]);

        return $this->mapper->mapListUsersResult($result);
    }
}
