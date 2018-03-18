<?php

declare(strict_types=1);

namespace Incognito\CognitoClient;

use Aws\Command;
use Aws\Exception\AwsException;
use Aws\Result;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient as CognitoClient;
use Incognito\CognitoClient\Exception\NotAuthorizedException;
use Incognito\CognitoClient\Exception\UserNotConfirmedException;
use Incognito\CognitoClient\Exception\UserNotFoundException;
use Incognito\Entity\User;
use Incognito\Entity\UserAttribute\UserAttribute;
use Incognito\Entity\UserAttribute\UserAttributeCollection;
use Incognito\Entity\Username;
use PHPUnit\Framework\TestCase;

class UserAuthenticationServiceTest extends TestCase
{
    /**
     * The payload expected for a login request (`AdminInitiateAuth`)
     *
     * @var array
     */
    private const LOGIN_PAYLOAD = [
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
    ];

    /**
     * The payload expected for a refresh token request (`AdminInitiateAuth`)
     *
     * @var array
     */
    private const REFRESH_TOKEN_PAYLOAD = [
        [
            'AuthFlow'   => 'REFRESH_TOKEN',
            'ClientId'   => 'someCognitoClientId',
            'UserPoolId' => 'someCognitoUserPoolId',
            'AuthParameters' => [
                'REFRESH_TOKEN' => 'some-refresh-token',
                'SECRET_HASH' => 'leH+ElshqALx+Oe0f20zk2dIr98jj0uwXwuKcQiQa0A=',
            ],
        ]
    ];

    public function testConstruct(): void
    {
        $sut = new UserAuthenticationService(
            $this->getCognitoClientMock(),
            $this->getCognitoCredentials()
        );

        $this->assertInstanceOf(
            UserAuthenticationService::class,
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
                self::LOGIN_PAYLOAD
            )
            ->willReturn($this->getAwsResult());

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->loginUser('some-username', 'some-password');
    }

    public function testLoginUserThrowsGenericException(): void
    {
        $this->expectException(\Exception::class);

        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'adminInitiateAuth',
                self::LOGIN_PAYLOAD
            )
            ->willThrowException(new \Exception());

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->loginUser('some-username', 'some-password');
    }

    public function testLoginUserThrowsGenericAwsException(): void
    {
        $this->expectException(AwsException::class);

        $awsException = new AwsException(
            'some-message',
            new Command('some-command')
        );

        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'adminInitiateAuth',
                self::LOGIN_PAYLOAD
            )
            ->willThrowException($awsException);

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->loginUser('some-username', 'some-password');
    }

    public function testLoginUserThrowsNotAuthorizedException(): void
    {
        $this->expectException(NotAuthorizedException::class);

        $awsException = new AwsException(
            'some-message',
            new Command('some-command'),
            [
                'code' => 'NotAuthorizedException'
            ]
        );

        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'adminInitiateAuth',
                self::LOGIN_PAYLOAD
            )
            ->willThrowException($awsException);

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->loginUser('some-username', 'some-password');
    }

    public function testLoginUserThrowsUserNotFoundException(): void
    {
        $this->expectException(UserNotFoundException::class);

        $awsException = new AwsException(
            'some-message',
            new Command('some-command'),
            [
                'code' => 'UserNotFoundException'
            ]
        );

        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'adminInitiateAuth',
                self::LOGIN_PAYLOAD
            )
            ->willThrowException($awsException);

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->loginUser('some-username', 'some-password');
    }

    public function testLoginUserThrowsUserNotConfirmedException(): void
    {
        $this->expectException(UserNotConfirmedException::class);

        $awsException = new AwsException(
            'some-message',
            new Command('some-command'),
            [
                'code' => 'UserNotConfirmedException'
            ]
        );

        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'adminInitiateAuth',
                self::LOGIN_PAYLOAD
            )
            ->willThrowException($awsException);

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->loginUser('some-username', 'some-password');
    }

    public function testRefreshToken(): void
    {
        $expectedResult = $this->getAwsResult();

        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'adminInitiateAuth',
                self::REFRESH_TOKEN_PAYLOAD
            )
            ->willReturn($expectedResult);

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $this->assertEquals(
            $expectedResult,
            $sut->refreshToken('some-username', 'some-refresh-token')
        );
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

        $sut = new UserAuthenticationService(
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
