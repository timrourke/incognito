<?php

declare(strict_types=1);

namespace Incognito\Entity;

use Assert\Assertion;

class User
{
    /**
     * The possible statuses an AWS Cognito User can be in
     *
     * @var array
     */
    private const VALID_STATUSES = [
        'UNCONFIRMED',
        'CONFIRMED',
        'ARCHIVED',
        'COMPROMISED',
        'UNKNOWN',
        'RESET_REQUIRED',
        'FORCE_CHANGE_PASSWORD',
    ];

    /**
     * The user's UUID
     *
     * @var string
     */
    private $id;

    /**
     * The user's username
     *
     * @var \Incognito\Entity\Username
     */
    private $username;

    /**
     * The date the user was created at
     *
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * The date the user was last updated at
     *
     * @var \DateTimeImmutable
     */
    private $updatedAt;

    /**
     * Whether the AWS Cognito User is enabled
     *
     * @var bool
     */
    private $enabled;

    /**
     * The current status for the user
     *
     * @var string
     */
    private $status = 'UNKNOWN';

    /**
     * The user attributes for this user
     *
     * @var \Incognito\Entity\UserAttributeCollection
     */
    private $userAttributes;

    /**
     * Constructor.
     *
     * @param \Incognito\Entity\Username $username
     * @param \Incognito\Entity\UserAttributeCollection $userAttributes
     */
    public function __construct(
        Username $username,
        UserAttributeCollection $userAttributes = null
    ) {
        $this->username = $username;
        $this->userAttributes = $userAttributes;
    }

    /**
     * Set a user attribute for this user
     *
     * @param \Incognito\Entity\UserAttribute $userAttribute
     */
    public function setAttribute(UserAttribute $userAttribute): void
    {
        if (is_null($this->userAttributes)) {
            $this->userAttributes = new UserAttributeCollection();
        }

        $this->userAttributes->add($userAttribute);
    }

    /**
     * Get a user attribute by name
     *
     * @param string $name
     * @return UserAttribute|null
     */
    public function getAttribute(string $name): ?UserAttribute
    {
        return $this->userAttributes->get($name);
    }

    /**
     * Get the array of user attributes for this user
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return is_null($this->userAttributes) ?
            [] :
            $this->userAttributes->toArray();
    }

    /**
     * Get the user's ID
     *
     * @return string
     */
    public function id(): string
    {
        return (string) $this->id;
    }

    /**
     * Set the user's ID
     *
     * @param string $id
     * @return \Incognito\Entity\User
     */
    public function setId(string $id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the user's username
     *
     * @return string
     */
    public function username(): string
    {
        return (string) $this->username;
    }

    /**
     * Set the user's username
     *
     * @param Username $username
     * @return \Incognito\Entity\User
     */
    public function setUsername(Username $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the date the user was created at
     *
     * @return \DateTimeImmutable|null
     */
    public function createdAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the date the user was created at
     *
     * @param \DateTimeImmutable $createdAt
     * @return \Incognito\Entity\User
     * @throws \Assert\AssertionFailedException
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): User
    {
        Assertion::null(
            $this->createdAt,
            'Invalid createdAt: user already has a "createdAt" date.'
        );

        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the date the user was last updated at
     *
     * @return \DateTimeImmutable|null
     */
    public function updatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Set the date the user was last updated at
     *
     * @param \DateTimeImmutable $updatedAt
     * @return \Incognito\Entity\User
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): User
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get whether this user is enabled
     *
     * @return bool|null
     */
    public function enabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * Set whether this user is enabled
     *
     * @param bool $enabled
     * @return \Incognito\Entity\User
     */
    public function setEnabled(bool $enabled): User
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get the user's status
     *
     * @return string
     */
    public function status(): string {
        return $this->status;
    }

    /**
     * Set the user's status
     *
     * @param string $status
     * @return \Incognito\Entity\User
     * @throws \Assert\AssertionFailedException
     */
    public function setStatus(string $status): User
    {
        Assertion::inArray(
            $status,
            self::VALID_STATUSES,
            sprintf(
                "Invalid status: must provide a valid status, received: \"%s\"",
                $status
            )
        );

        $this->status = $status;

        return $this;
    }
}