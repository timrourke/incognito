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
    private const STATUS_UNCONFIRMED           = 'UNCONFIRMED';
    private const STATUS_CONFIRMED             = 'CONFIRMED';
    private const STATUS_ARCHIVED              = 'ARCHIVED';
    private const STATUS_COMPROMISED           = 'COMPROMISED';
    private const STATUS_UNKNOWN               = 'UNKNOWN';
    private const STATUS_RESET_REQUIRED        = 'RESET_REQUIRED';
    private const STATUS_FORCE_CHANGE_PASSWORD = 'FORCE_CHANGE_PASSWORD';

    /**
     * The possible statuses an AWS Cognito User can be in
     *
     * @var array
     */
    private const VALID_STATUSES = [
        self::STATUS_UNCONFIRMED,
        self::STATUS_CONFIRMED,
        self::STATUS_ARCHIVED,
        self::STATUS_COMPROMISED,
        self::STATUS_UNKNOWN,
        self::STATUS_RESET_REQUIRED,
        self::STATUS_FORCE_CHANGE_PASSWORD,
    ];

    /**
     * @var string
     */
    private $status;

    /**
     * UserStatus constructor.
     *
     * @param  string $status
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $status = '')
    {
        if (!$status) {
            $status = self::STATUS_UNKNOWN;
        }

        $this->setStatus($status);
    }

    /**
     * Set the user's status
     *
     * @param  string $status
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
