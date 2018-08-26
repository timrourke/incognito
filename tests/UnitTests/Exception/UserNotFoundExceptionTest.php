<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Exception;

use Aws\Command;
use Aws\Exception\AwsException;
use Incognito\Exception\UserNotFoundException;
use PHPUnit\Framework\TestCase;

class UserNotFoundExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $awsException = $this->getAwsException();

        $sut = new UserNotFoundException($awsException);

        $this->assertInstanceOf(
            UserNotFoundException::class,
            $sut
        );
    }

    public function testGetMessage(): void
    {
        $awsException = $this->getAwsException();

        $sut = new UserNotFoundException($awsException);

        $this->assertEquals(
            'User not found.',
            $sut->getMessage()
        );
    }

    public function testGetCode(): void
    {
        $awsException = $this->getAwsException();

        $sut = new UserNotFoundException($awsException);

        $this->assertEquals(
            404,
            $sut->getCode()
        );
    }

    public function testGetPrevious(): void
    {
        $awsException = $this->getAwsException();

        $sut = new UserNotFoundException($awsException);

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
