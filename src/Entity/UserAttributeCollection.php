<?php

declare(strict_types=1);

namespace Incognito\Entity;

use Assert\Assertion;

class UserAttributeCollection
{
    /**
     * @var \Incognito\Entity\UserAttribute[]
     */
    private $userAttributes;

    /**
     * Constructor.
     *
     * @param \Incognito\Entity\UserAttribute[] $userAttributes
     */
    public function __construct(array $userAttributes = [])
    {
        $this->setUserAttributes($userAttributes);
    }

    /**
     * Add a UserAttribute to the collection.
     *
     * If the collection already contains a UserAttribute bearing the same name,
     * it will be replaced with the new one provided to this method.
     *
     * @param UserAttribute $newUserAttribute
     * @return UserAttributeCollection
     */
    public function add(UserAttribute $newUserAttribute): UserAttributeCollection
    {
        $this->userAttributes = array_merge(
            array_filter(
                $this->userAttributes,
                function(UserAttribute $attr) use ($newUserAttribute) {
                    return $attr->name() !== $newUserAttribute->name();
                }
            ),
            [
                $newUserAttribute
            ]
        );

        return $this;
    }

    /**
     * Get a UserAttribute by name
     *
     * @param string $name
     * @return \Incognito\Entity\UserAttribute|null
     */
    public function get(string $name): ?UserAttribute
    {
        return array_reduce(
            $this->userAttributes,
            function(?UserAttribute $acc, UserAttribute $current) use ($name) {
                if ($current->name() === $name) {
                    $acc = $current;
                }

                return $acc;
            },
            null
        );
    }

    /**
     * Get the collection of UserAttributes sorted alphabetically by name
     *
     * @return \Incognito\Entity\UserAttribute[]
     */
    public function toArray(): array
    {
        usort(
            $this->userAttributes,
            function(UserAttribute $a, UserAttribute $b) {
                return ($a->name() > $b->name()) ?
                    1 :
                    -1;
            }
        );

        return $this->userAttributes;
    }

    /**
     * @param \Incognito\Entity\UserAttribute[] $userAttributes
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    private function setUserAttributes(array $userAttributes): void
    {
        $this->assertUserAttributesAllValidType($userAttributes);

        $this->assertUserAttributesUniqueByName($userAttributes);

        $this->userAttributes = $userAttributes;
    }

    /**
     * @param \Incognito\Entity\UserAttribute[] $userAttributes
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    private function assertUserAttributesAllValidType(array $userAttributes): void
    {
        Assertion::allIsInstanceOf(
            $userAttributes,
            UserAttribute::class,
            "Invalid user attributes: all elements in the array must be of type \"\\Incognito\\Entity\\UserAttribute\"."
        );
    }

    /**
     * @param \Incognito\Entity\UserAttribute[] $userAttributes
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    private function assertUserAttributesUniqueByName(array $userAttributes): void
    {
        $mapOfAttrsByName = array_reduce(
            $userAttributes,
            function(array $acc, UserAttribute $current) {
                $acc[$current->name()][] = $current;

                return $acc;
            },
            []
        );

        $nonUniqueAttrs = array_filter(
            $mapOfAttrsByName,
            function($element, $key) use ($mapOfAttrsByName) {
                return count($mapOfAttrsByName[$key]) > 1;
            },
            ARRAY_FILTER_USE_BOTH
        );

        Assertion::eq(
            count($nonUniqueAttrs),
            0,
            sprintf(
                "Invalid user attributes: array of attributes must be unique by name. Non-unique attributes: %s",
                implode(', ', array_keys($nonUniqueAttrs))
            )
        );
    }
}