<?php

declare(strict_types=1);

namespace Incognito\CognitoClient;

use Aws\Result;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient as CognitoClient;
use PHPUnit\Framework\TestCase;

class UserAuthenticationTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new UserAuthentication(
            $this->getCognitoClientMock(),
            $this->getCognitoCredentials()
        );

        $this->assertInstanceOf(
            UserAuthentication::class,
            $sut
        );
    }

    public function testLoginUser(): void
    {
        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'adminInitiateAuth',
                [
                    [
                        'AuthFlow'   => 'ADMIN_NO_SRP_AUTH',
                        'ClientId'   => 'someCognitoClientId',
                        'UserPoolId' => 'someCognitoUserPoolId',
                        'AuthParameters' => [
                            'SECRET_HASH' => 'leH+ElshqALx+Oe0f20zk2dIr98jj0uwXwuKcQiQa0A=',
                            'USERNAME'    => 'some-username',
                            'PASSWORD'    => 'some-password',
                        ],
                    ]
                ]
            )
            ->willReturn($this->getAwsResult());

        $sut = new UserAuthentication(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->loginUser('some-username', 'some-password');
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
