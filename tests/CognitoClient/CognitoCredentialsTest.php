<?php

namespace Incognito\CognitoClient;

use PHPUnit\Framework\TestCase;

class CognitoCredentialsTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new CognitoCredentials(
            'someCognitoClientId',
            'someCognitoClientSecret',
            'someCognitoUserPoolId'
        );

        $this->assertInstanceOf(
            CognitoCredentials::class,
            $sut
        );
    }

    public function testGetClientId(): void
    {
        $sut = new CognitoCredentials(
            'someCognitoClientId',
            'someCognitoClientSecret',
            'someCognitoUserPoolId'
        );

        $this->assertEquals(
            'someCognitoClientId',
            $sut->getClientId()
        );
    }

    public function testGetClientSecret(): void
    {
        $sut = new CognitoCredentials(
            'someCognitoClientId',
            'someCognitoClientSecret',
            'someCognitoUserPoolId'
        );

        $this->assertEquals(
            'someCognitoClientSecret',
            $sut->getClientSecret()
        );
    }

    public function testGetUserPoolId(): void
    {
        $sut = new CognitoCredentials(
            'someCognitoClientId',
            'someCognitoClientSecret',
            'someCognitoUserPoolId'
        );

        $this->assertEquals(
            'someCognitoUserPoolId',
            $sut->getUserPoolId()
        );
    }
}
