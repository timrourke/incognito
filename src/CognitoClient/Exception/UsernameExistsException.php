<?php

declare(strict_types=1);

namespace Incognito\CognitoClient\Exception;

use Aws\Exception\AwsException;
use \Exception;

/**
 * Class UsernameExistsException
 *
 * @package Incognito\CognitoClient\Exception
 */
class UsernameExistsException extends Exception
{
    /**
     * @var int
     */
    private const CODE = 409;

    /**
     * @var string
     */
    private const MESSAGE = 'Username already exists.';

    /**
     * UsernameExistsException constructor.
     *
     * @param \Aws\Exception\AwsException|null $previous
     */
    public function __construct(AwsException $previous = null)
    {
        parent::__construct(self::MESSAGE, self::CODE, $previous);
    }
}