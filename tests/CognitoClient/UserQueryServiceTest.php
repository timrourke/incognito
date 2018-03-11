<?php

declare(strict_types=1);

namespace Incognito\CognitoClient;

use Aws\Result;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient as CognitoClient;
use PHPUnit\Framework\TestCase;

class UserQueryServiceTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new UserQueryService(
            $this->getCognitoClientMock(),
            $this->getCognitoCredentials()
        );

        $this->assertInstanceOf(
            UserQueryService::class,
            $sut
        );
    }

    public function testGetUserByUsername(): void
    {
        $expectedResult = $this->getAwsResult();

        $client = $this->getCognitoClientMock();

        $client->expects($this->once())
            ->method('__call')
            ->with('adminGetUser', [
                [
                    'UserPoolId' => 'someCognitoUserPoolId',
                    'Username' => 'some-username'
                ]
            ])
            ->willReturn($expectedResult);

        $sut = new UserQueryService(
            $client,
            $this->getCognitoCredentials()
        );

        $this->assertEquals(
            $expectedResult,
            $sut->getUserByUsername('some-username')
        );
    }

    public function testGetList(): void
    {
        $expectedResult = $this->getAwsResult();

        $client = $this->getCognitoClientMock();

        $client->expects($this->once())
            ->method('__call')
            ->with('listUsers', [
                [
                    'UserPoolId' => 'someCognitoUserPoolId',
                ],
            ])
            ->willReturn($expectedResult);

        $sut = new UserQueryService(
            $client,
            $this->getCognitoCredentials()
        );

        $this->assertEquals(
            $expectedResult,
            $sut->getList()
        );
    }

    private function getCognitoClientMock()
    {
        return $this->getMockBuilder(CognitoClient::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getCognitoCredentials(): CognitoCredentials
    {
        return new CognitoCredentials(
            'someCognitoClientId',
            'someCognitoClientSecret',
            'someCognitoUserPoolId'
        );
    }

    private function getAwsResult()
    {
        return $this->getMockBuilder(Result::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
