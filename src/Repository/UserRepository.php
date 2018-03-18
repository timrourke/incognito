<?php

declare(strict_types=1);

namespace Incognito\Repository;

use Aws\Exception\AwsException;
use Incognito\CognitoClient\Exception\UserNotFoundException;
use Incognito\Entity\User;
use Incognito\Mapper\UserMapper;
use Incognito\CognitoClient\UserQueryService;

class UserRepository
{
    /**
     * @var \Incognito\Mapper\UserMapper
     */
    private $mapper;

    /**
     * @var \Incognito\CognitoClient\UserQueryService
     */
    private $queryService;

    /**
     * UserRepository constructor.
     *
     * @param \Incognito\Mapper\UserMapper $mapper
     * @param \Incognito\CognitoClient\UserQueryService $queryService
     */
    public function __construct(
        UserMapper $mapper,
        UserQueryService $queryService
    )
    {
        $this->mapper = $mapper;
        $this->queryService = $queryService;
    }

    /**
     * Get a User by username
     *
     * @param string $username
     * @return \Incognito\Entity\User
     */
    public function find(string $username): User
    {
        $result = null;

        try {
            $result = $this->queryService->getUserByUsername($username);
        } catch(AwsException $e) {
            $this->handleFindAwsException($e);
        }

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
        $result = $this->queryService->getList();

        return $this->mapper->mapListUsersResult($result);
    }

    /**
     * @param \Aws\Exception\AwsException $e
     * @throws \Incognito\CognitoClient\Exception\UserNotFoundException
     */
    private function handleFindAwsException(AwsException $e): void
    {
       switch($e->getAwsErrorCode()) {
           case 'UserNotFoundException':
               throw new UserNotFoundException($e);
           default:
               throw $e;
       }
    }
}
