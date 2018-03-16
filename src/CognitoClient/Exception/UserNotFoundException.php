<?php

declare(strict_types=1);

namespace Incognito\CognitoClient\Exception;

use Aws\Exception\AwsException;
use Exception;

/**
 * Class UserNotFoundException
 *
 * Useful when a User can't be found
 *
 * @package Incognito\CognitoClient\Exception
 */
class UserNotFoundException extends Exception
{
    /**
     * @var int
     */
    private const CODE = 404;

    /**
     * @var string
     */
    private const MESSAGE = 'User not found.';

    /**
     * UserNotFoundException constructor.
     *
     * @param \Aws\Exception\AwsException|null $previous
     */
    public function __construct(AwsException $previous = null)
    {
        parent::__construct(self::MESSAGE, self::CODE, $previous);
    }
}
