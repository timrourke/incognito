<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Exception;

use Aws\Command;
use Aws\Exception\AwsException;
use Incognito\Exception\ExceptionFactory;
use Incognito\Exception\UserNotFoundException;
use PHPUnit\Framework\TestCase;

class ExceptionFactoryTest extends TestCase
{
    public function testMake(): void
    {
        $genericAwsException = new AwsException(
            'some-message',
            new Command('some-command')
        );

        $actual = ExceptionFactory::make($genericAwsException);

        static::assertInstanceOf(
            AwsException::class,
            $actual
        );
    }

    public function testMakeThrowsSpecificClassIfItExists(): void
    {
        $specificException = new AwsException(
            'some-message',
            new Command('some-command'),
            [
                'code' => 'UserNotFoundException',
            ]
        );

        $actual = ExceptionFactory::make($specificException);

        static::assertInstanceOf(
            UserNotFoundException::class,
            $actual
        );
    }
}
