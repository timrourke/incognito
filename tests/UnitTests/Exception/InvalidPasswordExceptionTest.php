<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Exception;

use Aws\Command;
use Aws\Exception\AwsException;
use Incognito\Exception\InvalidPasswordException;
use PHPUnit\Framework\TestCase;

class InvalidPasswordExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $awsException = $this->getAwsException();

        $sut = new InvalidPasswordException($awsException);

        static::assertInstanceOf(
            InvalidPasswordException::class,
            $sut
        );
    }

    public function testGetMessage(): void
    {
        $awsException = $this->getAwsException();

        $sut = new InvalidPasswordException($awsException);

        static::assertEquals(
            'Invalid password.',
            $sut->getMessage()
        );
    }

    public function testGetCode(): void
    {
        $awsException = $this->getAwsException();

        $sut = new InvalidPasswordException($awsException);

        static::assertEquals(
            422,
            $sut->getCode()
        );
    }

    public function testGetPrevious(): void
    {
        $awsException = $this->getAwsException();

        $sut = new InvalidPasswordException($awsException);

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
