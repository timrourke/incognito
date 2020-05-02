<?php

declare(strict_types=1);

namespace Incognito\Entity\UserAttribute;

use Assert\Assertion;

class UserAttribute implements UserAttributeInterface
{
    /**
     * @var string
     */
    protected const ALLOWABLE_CHARACTERS_REGEX = "/[\p{L}\p{M}\p{S}\p{N}\p{P}]+/u";

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $value;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $value
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $name, string $value = '')
    {
        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * Get the name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get the value
     *
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Set the name
     *
     * @param  string $name
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    protected function setName(string $name): void
    {
        $this->validateNameLength($name);

        $this->validateNameCharacters($name);

        $this->name = $name;
    }

    /**
     * Validate that the name's length is between 1 and 32 characters
     *
     * @param  string $name
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    protected function validateNameLength(string $name): void
    {
        Assertion::betweenLength(
            $name,
            1,
            32,
            sprintf(
                "Invalid name \"%s\": name must be between 1 and 32 characters in length.",
                $name
            )
        );
    }

    /**
     * Validate that the name's characters are valid
     *
     * @param  string $name
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    protected function validateNameCharacters(string $name): void
    {
        Assertion::regex(
            $name,
            self::ALLOWABLE_CHARACTERS_REGEX,
            sprintf(
                "Invalid name \"%s\": name contains invalid characters.",
                $name
            )
        );
    }

    /**
     * Set the value
     *
     * @param  string $value
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    protected function setValue(string $value): void
    {
        $this->validateValueLength($value);

        $this->value = $value;
    }

    /**
     * Validate that the value's length is between 0 and 2048 characters
     *
     * @param  string $value
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    protected function validateValueLength(string $value): void
    {
        Assertion::maxLength(
            $value,
            2048,
            sprintf(
                "Invalid value \"%s\": value must be between 0 and 2048 characters in length.",
                $value
            )
        );
    }
}
