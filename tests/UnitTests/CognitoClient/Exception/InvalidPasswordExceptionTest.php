<?php

declare(strict_types=1);

namespace Incognito\UnitTests\CognitoClient\Exception;

use Aws\Command;
use Aws\Exception\AwsException;
use Incognito\CognitoClient\Exception\InvalidPasswordException;
use PHPUnit\Framework\TestCase;

class InvalidPasswordExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $awsException = $this->getAwsException();

        $sut = new InvalidPasswordException($awsException);

        $this->assertInstanceOf(
            InvalidPasswordException::class,
            $sut
        );
    }

    public function testGetMessage(): void
    {
        $awsException = $this->getAwsException();

        $sut = new InvalidPasswordException($awsException);

        $this->assertEquals(
            'Invalid password.',
            $sut->getMessage()
        );
    }

    public function testGetCode(): void
    {
        $awsException = $this->getAwsException();

        $sut = new InvalidPasswordException($awsException);

        $this->assertEquals(
            422,
            $sut->getCode()
        );
    }

    public function testGetPrevious(): void
    {
        $awsException = $this->getAwsException();

        $sut = new InvalidPasswordException($awsException);

        $this->assertEquals(
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
