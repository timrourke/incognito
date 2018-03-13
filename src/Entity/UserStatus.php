<?php

declare(strict_types=1);

namespace Incognito\Entity;

use Assert\Assertion;

/**
 * Class UserStatus
 *
 * A value object representing the states an AWS Cognito User can be in
 *
 * @package Incognito\Entity
 */
class UserStatus
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
     * @var string
     */
    private $status;

    /**
     * UserStatus constructor.
     *
     * @param string $status
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $status)
    {
        $this->setStatus($status);
    }

    /**
     * Set the user's status
     *
     * @param string $status
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    private function setStatus(string $status): void
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
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->status;
    }
}