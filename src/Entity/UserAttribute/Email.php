<?php

declare(strict_types=1);

namespace Incognito\Entity\UserAttribute;

use Assert\Assertion;

class Email extends UserAttribute implements UserAttributeInterface
{
    /**
     * @var string
     */
    private const NAME = 'email';

    /**
     * Email constructor.
     *
     * @param string $value
     * @throws \Assert\AssertionFailedException
     */
    public function __construct($value = '')
    {
        parent::__construct(self::NAME, $value);
    }

    /**
     * Set the email address
     *
     * @param string $value
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    protected function setValue(string $value): void
    {
        $this->validateValueIsEmail($value);

        parent::setValue($value);
    }

    /**
     * Validate that the value is a valid email address
     *
     * @param string $value
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    private function validateValueIsEmail(string $value): void
    {
        Assertion::email(
            $value,
            "Invalid email: \"%s\" must be a valid email address."
        );
    }
}