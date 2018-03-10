<?php

declare(strict_types=1);

namespace Incognito\Entity;

use Assert\Assertion;

class User
{
    /**
     * The user's UUID
     *
     * @var string
     */
    private $id;

    /**
     * @var \Incognito\Entity\Username
     */
    private $username;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var \DateTimeImmutable
     */
    private $updatedAt;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var string
     */
    private $status = 'UNKNOWN';

    /**
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
     * @param string $name
     * @return UserAttribute|null
     */
    public function getAttribute(string $name): ?UserAttribute
    {
        return $this->userAttributes->get($name);
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->userAttributes->toArray();
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return (string) $this->id;
    }

    /**
     * @param string $id
     * @return \Incognito\Entity\User
     */
    public function setId(string $id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function username(): string
    {
        return (string) $this->username;
    }

    /**
     * @param Username $username
     * @return \Incognito\Entity\User
     */
    public function setUsername(Username $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function createdAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * @return \Incognito\Entity\User
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): User
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function updatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeImmutable $updatedAt
     * @return \Incognito\Entity\User
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): User
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function enabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return \Incognito\Entity\User
     */
    public function setEnabled(bool $enabled): User
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function status(): string {
        return $this->status;
    }

    /**
     * @param string $status
     * @return \Incognito\Entity\User
     * @throws \Assert\AssertionFailedException
     */
    public function setStatus(string $status): User
    {
        Assertion::inArray(
            $status,
            [
                'UNCONFIRMED',
                'CONFIRMED',
                'ARCHIVED',
                'COMPROMISED',
                'UNKNOWN',
                'RESET_REQUIRED',
                'FORCE_CHANGE_PASSWORD',
            ],
            sprintf(
                "Invalid status: must provide a valid status, received: \"%s\"",
                $status
            )
        );

        $this->status = $status;

        return $this;
    }
}