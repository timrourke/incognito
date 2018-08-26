<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Exception;

use Aws\Command;
use Aws\Exception\AwsException;
use Incognito\Exception\NotAuthorizedException;
use PHPUnit\Framework\TestCase;

class NotAuthorizedExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $awsException = $this->getAwsException();

        $sut = new NotAuthorizedException($awsException);

        $this->assertInstanceOf(
            NotAuthorizedException::class,
            $sut
        );
    }

    public function testGetMessage(): void
    {
        $awsException = $this->getAwsException();

        $sut = new NotAuthorizedException($awsException);

        $this->assertEquals(
            'Login failed: Incorrect username or password.',
            $sut->getMessage()
        );
    }

    public function testGetCode(): void
    {
        $awsException = $this->getAwsException();

        $sut = new NotAuthorizedException($awsException);

        $this->assertEquals(
            401,
            $sut->getCode()
        );
    }

    public function testGetPrevious(): void
    {
        $awsException = $this->getAwsException();

        $sut = new NotAuthorizedException($awsException);

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
