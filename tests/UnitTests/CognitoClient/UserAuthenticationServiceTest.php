<?php

declare(strict_types=1);

namespace Incognito\UnitTests\CognitoClient;

use Aws\Command;
use Aws\Exception\AwsException;
use Aws\Result;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient as CognitoClient;
use Incognito\CognitoClient\CognitoCredentials;
use Incognito\CognitoClient\Exception\InvalidPasswordException;
use Incognito\CognitoClient\Exception\NotAuthorizedException;
use Incognito\CognitoClient\Exception\UsernameExistsException;
use Incognito\CognitoClient\Exception\UserNotConfirmedException;
use Incognito\CognitoClient\Exception\UserNotFoundException;
use Incognito\CognitoClient\UserAuthenticationService;
use Incognito\Entity\Password;
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
            'AuthFlow'   => 'REFRESH_TOKEN_AUTH',
            'ClientId'   => 'someCognitoClientId',
            'UserPoolId' => 'someCognitoUserPoolId',
            'AuthParameters' => [
                'REFRESH_TOKEN' => 'some-refresh-token',
                'SECRET_HASH'   => 'leH+ElshqALx+Oe0f20zk2dIr98jj0uwXwuKcQiQa0A=',
                'USERNAME'      => 'some-username',
            ],
        ]
    ];

    /**
     * The payload expected for a sign up request (`SignUp`)
     *
     * @var array
     */
    private const SIGN_UP_PAYLOAD = [
        [
            'ClientId' => 'someCognitoClientId',
            'Password' => 'SomePassword123!',
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
    ];

    private const CHANGE_PASSWORD_PAYLOAD = [
        [
            'AccessToken' => 'some-access-token',
            'PreviousPassword' => 'Some-old-password!123',
            'ProposedPassword' => 'Some-new-password!123',
        ],
    ];

    private const ADMIN_CONFIRM_SIGNUP_PAYLOAD = [
        [
            'Username'   => 'some-username',
            'UserPoolId' => 'someCognitoUserPoolId',
        ],
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
                self::SIGN_UP_PAYLOAD
            )
            ->willReturn($this->getAwsResult());

        $user = $this->getSignUpUser();

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->signUpUser($user, new Password('SomePassword123!'));
    }

    public function testSignUpUserThrowsGenericException(): void
    {
        $this->expectException(\Exception::class);

        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'signUp',
                self::SIGN_UP_PAYLOAD
            )
            ->willThrowException(new \Exception());

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->signUpUser($this->getSignUpUser(), new Password('SomePassword123!'));
    }

    public function testSignUpUserThrowsGenericAwsException(): void
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
                'signUp',
                self::SIGN_UP_PAYLOAD
            )
            ->willThrowException($awsException);

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->signUpUser($this->getSignUpUser(), new Password('SomePassword123!'));
    }

    public function testSignUpUserThrowsUsernameExistsException(): void
    {
        $this->expectException(UsernameExistsException::class);

        $awsException = new AwsException(
            'some-message',
            new Command('some-command'),
            [
                'code' => 'UsernameExistsException'
            ]
        );

        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'signUp',
                self::SIGN_UP_PAYLOAD
            )
            ->willThrowException($awsException);

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->signUpUser($this->getSignUpUser(), new Password('SomePassword123!'));
    }

    public function testChangePassword(): void
    {
        $oldPassword = new Password('Some-old-password!123');
        $newPassword = new Password('Some-new-password!123');

        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'changePassword',
                self::CHANGE_PASSWORD_PAYLOAD
            )
            ->willReturn($this->getAwsResult());

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->changePassword(
            self::CHANGE_PASSWORD_PAYLOAD[0]['AccessToken'],
            $oldPassword,
            $newPassword
        );
    }

    public function testChangePasswordThrowsInvalidPasswordException(): void
    {
        $this->expectException(InvalidPasswordException::class);

        $awsException = new AwsException(
            'some-message',
            new Command('some-command'),
            [
                'code' => 'InvalidPasswordException'
            ]
        );

        $oldPassword = new Password('Some-old-password!123');
        $newPassword = new Password('Some-new-password!123');

        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'changePassword',
                self::CHANGE_PASSWORD_PAYLOAD
            )
            ->willThrowException($awsException);

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->changePassword(
            self::CHANGE_PASSWORD_PAYLOAD[0]['AccessToken'],
            $oldPassword,
            $newPassword
        );
    }

    public function testChangePasswordThrowsGenericException(): void
    {
        $this->expectException(AwsException::class);

        $awsException = new AwsException(
            'some-message',
            new Command('some-command')
        );

        $oldPassword = new Password('Some-old-password!123');
        $newPassword = new Password('Some-new-password!123');

        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'changePassword',
                self::CHANGE_PASSWORD_PAYLOAD
            )
            ->willThrowException($awsException);

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->changePassword(
            self::CHANGE_PASSWORD_PAYLOAD[0]['AccessToken'],
            $oldPassword,
            $newPassword
        );
    }

    public function testAdminConfirmSignup(): void
    {
        $clientMock = $this->getCognitoClientMock();

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'adminConfirmSignUp',
                self::ADMIN_CONFIRM_SIGNUP_PAYLOAD
            )
            ->willReturn($this->getAwsResult());

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->adminConfirmSignUp(self::ADMIN_CONFIRM_SIGNUP_PAYLOAD[0]['Username']);
    }

    public function testAdminConfirmSignupThrowsGenericException(): void
    {
        $this->expectException(AwsException::class);

        $clientMock = $this->getCognitoClientMock();

        $awsException = new AwsException(
            'some-message',
            new Command('some-command')
        );

        $clientMock->expects($this->once())
            ->method('__call')
            ->with(
                'adminConfirmSignUp',
                self::ADMIN_CONFIRM_SIGNUP_PAYLOAD
            )
            ->willThrowException($awsException);

        $sut = new UserAuthenticationService(
            $clientMock,
            $this->getCognitoCredentials()
        );

        $sut->adminConfirmSignUp(self::ADMIN_CONFIRM_SIGNUP_PAYLOAD[0]['Username']);
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

    private function getSignUpUser(): User
    {
        return new User(
            new Username('some-username'),
            new UserAttributeCollection([
                new UserAttribute('email', 'somebody@somewhere.com'),
                new UserAttribute('family_name', 'Klein'),
                new UserAttribute('given_name', 'Val'),
            ])
        );
    }
}
