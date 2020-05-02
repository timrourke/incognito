<?php

declare(strict_types=1);

namespace Incognito\Entity;

use Assert\Assertion;

class Username
{
    /**
     * @var string
     */
    private const ALLOWABLE_CHARACTERS_REGEX = "/[\p{L}\p{M}\p{S}\p{N}\p{P}]+/u";

    /**
     * @var string
     */
    private $username;

    /**
     * Constructor.
     *
     * @param string $username
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $username)
    {
        $this->setUsername($username);
    }

    /**
     * @return string
     */
    public function username(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->username;
    }

    /**
     * Validate and set the username
     *
     * @param  string $username
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    private function setUsername(string $username): void
    {
        Assertion::betweenLength(
            $username,
            1,
            128,
            sprintf(
                "Invalid username \"%s\": username must be between 1 and 128 characters in length.",
                $username
            )
        );

        Assertion::regex(
            $username,
            self::ALLOWABLE_CHARACTERS_REGEX,
            sprintf(
                "Invalid username \"%s\": username contains invalid characters.",
                $username
            )
        );

        $this->username = $username;
    }
}
