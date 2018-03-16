<?php

declare(strict_types=1);

namespace Incognito\CognitoClient\Exception;

use Aws\Exception\AwsException;
use Exception;

/**
 * Class UserNotConfirmedException
 *
 * Useful when a User has not confirmed their registration.
 *
 * @package Incognito\CognitoClient\Exception
 */
class UserNotConfirmedException extends Exception
{
    /**
     * @var int
     */
    private const CODE = 401;

    /**
     * @var string
     */
    private const MESSAGE = 'Login failed: User not confirmed.';

    /**
     * UserNotConfirmedException constructor.
     *
     * @param \Aws\Exception\AwsException|null $previous
     */
    public function __construct(AwsException $previous = null)
    {
        parent::__construct(self::MESSAGE, self::CODE, $previous);
    }
}