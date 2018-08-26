<?php

declare(strict_types=1);

namespace Incognito\Exception;

use Aws\Exception\AwsException;
use Exception;

/**
 * Class NotAuthorizedException
 *
 * Useful when a login attempt is made with an incorrect username or password.
 *
 * @package Incognito\CognitoClient\Exception
 */
class NotAuthorizedException extends Exception
{
    /**
     * @var int
     */
    private const CODE = 401;

    /**
     * @var string
     */
    private const MESSAGE = 'Login failed: Incorrect username or password.';

    /**
     * NotAuthorizedException constructor.
     *
     * @param \Aws\Exception\AwsException|null $previous
     */
    public function __construct(AwsException $previous = null)
    {
        parent::__construct(self::MESSAGE, self::CODE, $previous);
    }
}
