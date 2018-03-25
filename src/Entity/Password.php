<?php

declare(strict_types=1);

namespace Incognito\Entity;

use Assert\Assertion;

class Password
{
    /**
     * Regular expression to match a password containing:
     *   - minimum 8 characters
     *   - lowercase characters
     *   - uppercase characters
     *   - special characters
     *
     * @var string
     */
    const PASSWORD_MATCH = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\^$\*\.\[\]{}\(\)\?\-\"!@#%&\/\\,><':;|_~`])/";

    /**
     * @var string
     */
    private $password;

    /**
     * Password constructor.
     *
     * @param string $password
     */
    public function __construct(string $password)
    {
        $this->setPassword($password);
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->password;
    }

    /**
     * Validate and set the password
     *
     * @param string $password
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    private function setPassword(string $password): void
    {
        Assertion::betweenLength(
            $password,
            8,
            256,
            'Invalid password: password must be between 8 and 256 characters in length.'
        );

        Assertion::regex(
            $password,
            self::PASSWORD_MATCH,
            'Invalid password: password must contain uppercase and lowercase letters, numbers, and special characters.'
        );

        $this->password = $password;
    }
}