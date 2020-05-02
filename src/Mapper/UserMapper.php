<?php

declare(strict_types=1);

namespace Incognito\Mapper;

use Aws\Result;
use DateTimeImmutable;
use Incognito\Entity\User;
use Incognito\Entity\UserAttribute\UserAttribute;
use Incognito\Entity\UserAttribute\UserAttributeCollection;
use Incognito\Entity\Username;
use Incognito\Entity\UserStatus;

/**
 * Class UserMapper
 *
 * @package Incognito\Mapper
 */
class UserMapper
{
    /**
     * Map an AWS SDK "AdminGetUser" Result to a User entity
     *
     * @param  \Aws\Result $result
     * @return \Incognito\Entity\User
     * @throws \Assert\AssertionFailedException
     */
    public function mapAdminGetUserResult(Result $result): User
    {
        return $this->buildUserFromResult($result->toArray());
    }

    /**
     * Map an AWS SDK "ListUsers" Result to an array of User entities
     *
     * @param  Result $result
     * @return \Incognito\Entity\User[]
     */
    public function mapListUsersResult(Result $result): array
    {
        return array_map(
            function (array $userData) {
                return $this->buildUserFromResult($userData);
            },
            $result->toArray()['Users']
        );
    }

    /**
     * Build a User entity from an AWS SDK Result
     *
     * @param  array $userData
     * @return \Incognito\Entity\User
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    private function buildUserFromResult(array $userData): User
    {
        $username = new Username((string) $userData['Username']);

        $userAttributeCollection =
            $this->buildUserAttributesCollectionFromResult($userData);

        $user = new User($username, $userAttributeCollection);

        return $user
            ->setId(
                $userAttributeCollection->get('sub')->value()
            )
            ->setCreatedAt(
                new DateTimeImmutable((string) $userData['UserCreateDate'])
            )
            ->setUpdatedAt(
                new DateTimeImmutable((string) $userData['UserLastModifiedDate'])
            )
            ->setEnabled(
                (bool) json_decode((string) $userData['Enabled'])
            )
            ->setStatus(
                new UserStatus((string) $userData['UserStatus'])
            );
    }

    /**
     * Build a UserAttributeCollection from an AWS SDK Result
     *
     * @param  array $userData
     * @return \Incognito\Entity\UserAttribute\UserAttributeCollection
     */
    private function buildUserAttributesCollectionFromResult(
        array $userData
    ): UserAttributeCollection {
        $attrsKey = array_key_exists('Attributes', $userData) ?
            'Attributes' :
            'UserAttributes';

        $userAttributes = array_map(
            function (array $attr) {
                return new UserAttribute($attr['Name'], $attr['Value']);
            },
            $userData[$attrsKey]
        );

        return new UserAttributeCollection($userAttributes);
    }
}
