<?php

declare(strict_types=1);

namespace Incognito\CognitoClient;

use Aws\Result;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient as CognitoClient;
use Incognito\Entity\User;
use Incognito\Entity\UserAttribute;
use Incognito\Entity\UserAttributeCollection;
use Incognito\Entity\Username;
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

    public function testSignUpUser(): void
    {
        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'signUp',
                [
                    [
                        'ClientId' => 'someCognitoClientId',
                        'Password' => 'some-password',
                        'SecretHash' => 'leH+ElshqALx+Oe0f20zk2dIr98jj0uwXwuKcQiQa0A=',
                        'UserAttributes' => [
                            [
                                'Name' => 'email',
                                'Value' => 'somebody@somewhere.com',
                            ],
                            [
                                'Name' => 'family_name',
                                'Value' => 'Klein',
                            ],
                            [
                                'Name' => 'given_name',
                                'Value' => 'Val',
                            ]
                        ],
                        'Username' => 'some-username',
                    ]
                ]
            )
            ->willReturn($this->getAwsResult());

        $user = new User(
            new Username('some-username'),
            new UserAttributeCollection([
                new UserAttribute('email', 'somebody@somewhere.com'),
                new UserAttribute('family_name', 'Klein'),
                new UserAttribute('given_name', 'Val'),
            ])
        );

        $sut = new UserAuthentication(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->signUpUser($user, 'some-password');
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
