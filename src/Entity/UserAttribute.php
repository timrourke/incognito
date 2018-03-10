<?php

declare(strict_types=1);

namespace Incognito\Entity;

use Assert\Assertion;

class UserAttribute
{
    /**
     * @var string
     */
    private const ALLOWABLE_CHARACTERS_REGEX = "/[\p{L}\p{M}\p{S}\p{N}\p{P}]+/u";

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value = '')
    {
        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @param string $name
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    private function setName(string $name): void
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

        Assertion::regex(
            $name,
            self::ALLOWABLE_CHARACTERS_REGEX,
            sprintf(
                "Invalid name \"%s\": name contains invalid characters.",
                $name
            )
        );

        $this->name = $name;
    }

    /**
     * @param string $value
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    private function setValue(string $value): void
    {
        Assertion::maxLength(
            $value,
            2048,
            sprintf(
                "Invalid value \"%s\": value must be between 0 and 2048 characters in length.",
                $value
            )
        );

        $this->value = $value;
    }
}