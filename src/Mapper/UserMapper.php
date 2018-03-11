<?php

declare(strict_types=1);

namespace Incognito\Mapper;

use Aws\Result;
use Incognito\Entity\User;
use Incognito\Entity\UserAttribute;
use Incognito\Entity\UserAttributeCollection;
use Incognito\Entity\Username;

class UserMapper
{
    /**
     * Map an AWS SDK "AdminGetUser" Result to a User entity
     *
     * @param \Aws\Result $result
     * @return \Incognito\Entity\User
     */
    public function mapAdminGetUserResult(Result $result): User
    {
        return $this->buildUserFromResult($result->toArray(), 'Attributes');
    }

    /**
     * Map an AWS SDK "ListUsers" Result to an array of User entities
     *
     * @param Result $result
     * @return \Incognito\Entity\User[]
     */
    public function mapListUsersResult(Result $result): array
    {
        return array_map(
            function(array $userData) {
                return $this->buildUserFromResult($userData, 'UserAttributes');
            },
            $result->toArray()['Users']
        );
    }

    /**
     * Build a User entity from an AWS SDK Result
     *
     * @param array $userData
     * @param string $userAttrsKey
     * @return \Incognito\Entity\User
     */
    private function buildUserFromResult(
        array $userData,
        string $userAttrsKey
    ): User {
        $username = new Username((string) $userData['Username']);

        $userAttributeCollection = $this->buildUserAttributesCollectionFromResult(
            $userData,
            $userAttrsKey
        );

        $user = new User($username, $userAttributeCollection);

        return $user
            ->setId((string) $userAttributeCollection->get('sub')->value())
            ->setCreatedAt(new \DateTimeImmutable((string) $userData['UserCreateDate']))
            ->setUpdatedAt(new \DateTimeImmutable((string) $userData['UserLastModifiedDate']))
            ->setEnabled((bool) json_decode((string) $userData['Enabled']))
            ->setStatus((string) $userData['UserStatus']);
    }

    /**
     * Build a UserAttributeCollection from an AWS SDK Result
     *
     * @param array $userData
     * @param string $userAttributesKey
     * @return \Incognito\Entity\UserAttributeCollection
     */
    private function buildUserAttributesCollectionFromResult(
        array $userData,
        string $userAttributesKey
    ): UserAttributeCollection {
        $userAttributes = array_map(
            function(array $attr) {
                return new UserAttribute($attr['Name'], $attr['Value']);
            },
            $userData[$userAttributesKey]
        );

        return new UserAttributeCollection($userAttributes);
    }
}