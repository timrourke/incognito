<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Exception;

use Aws\Command;
use Aws\Exception\AwsException;
use Incognito\Exception\UserNotConfirmedException;
use PHPUnit\Framework\TestCase;

class UserNotConfirmedExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $awsException = $this->getAwsException();

        $sut = new UserNotConfirmedException($awsException);

        static::assertInstanceOf(
            UserNotConfirmedException::class,
            $sut
        );
    }

    public function testGetMessage(): void
    {
        $awsException = $this->getAwsException();

        $sut = new UserNotConfirmedException($awsException);

        static::assertEquals(
            'Login failed: User not confirmed.',
            $sut->getMessage()
        );
    }

    public function testGetCode(): void
    {
        $awsException = $this->getAwsException();

        $sut = new UserNotConfirmedException($awsException);

        static::assertEquals(
            401,
            $sut->getCode()
        );
    }

    public function testGetPrevious(): void
    {
        $awsException = $this->getAwsException();

        $sut = new UserNotConfirmedException($awsException);

        static::assertEquals(
            $awsException,
            $sut->getPrevious()
        );
    }

    private function getAwsException(): AwsException
    {
        return new AwsException(
            'some message',
            new Command('some command')
        );
    }
}
