<?php

declare(strict_types=1);

namespace Incognito\Exception;

use Aws\Exception\AwsException;
use Exception;

/**
 * Class InvalidPasswordException
 *
 * Useful when creating or updating a user fails because a password is invalid.
 *
 * @package Incognito\CognitoClient\Exception
 */
class InvalidPasswordException extends Exception
{
    /**
     * @var int
     */
    private const CODE = 422;

    /**
     * @var string
     */
    private const MESSAGE = 'Invalid password.';

    /**
     * InvalidPasswordException constructor.
     *
     * @param \Aws\Exception\AwsException|null $previous
     */
    public function __construct(AwsException $previous = null)
    {
        parent::__construct(self::MESSAGE, self::CODE, $previous);
    }
}
