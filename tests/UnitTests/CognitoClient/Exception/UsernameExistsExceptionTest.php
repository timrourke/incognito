<?php

declare(strict_types=1);

namespace Incognito\UnitTests\CognitoClient\Exception;

use Aws\Command;
use Aws\Exception\AwsException;
use Incognito\CognitoClient\Exception\UsernameExistsException;
use PHPUnit\Framework\TestCase;

class UsernameExistsExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $awsException = $this->getAwsException();

        $sut = new UsernameExistsException($awsException);

        $this->assertInstanceOf(
            UsernameExistsException::class,
            $sut
        );
    }

    public function testGetMessage(): void
    {
        $awsException = $this->getAwsException();

        $sut = new UsernameExistsException($awsException);

        $this->assertEquals(
            'Username already exists.',
            $sut->getMessage()
        );
    }

    public function testGetCode(): void
    {
        $awsException = $this->getAwsException();

        $sut = new UsernameExistsException($awsException);

        $this->assertEquals(
            409,
            $sut->getCode()
        );
    }

    public function testGetPrevious(): void
    {
        $awsException = $this->getAwsException();

        $sut = new UsernameExistsException($awsException);

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
