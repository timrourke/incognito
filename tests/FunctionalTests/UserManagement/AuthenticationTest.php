<?php

declare(strict_types=1);

namespace Incognito\FunctionalTests\UserManagement;

use Incognito\Entity\Password;
use Incognito\Entity\User;
use Incognito\Entity\Username;
use Incognito\FunctionalTests\UserAuthenticationServiceFactory;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{
    private const USERNAME = 'someValidUserName';

    private const PASSWORD = 'SomeValidPassword123!';

    /**
     * @var string
     */
    private $userId;

    /**
     * @var \Incognito\CognitoClient\UserAuthenticationService
     */
    private $userAuthenticationService;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->userAuthenticationService = UserAuthenticationServiceFactory::build();

        parent::setUp();
    }

    /**
     * @test
     */
    public function shouldLogInUser(): void
    {
        $this->createUser();

        $result = $this->userAuthenticationService->loginUser(
            self::USERNAME,
            self::PASSWORD
        );

        $authenticationResult = $result['AuthenticationResult'];

        $this->assertEquals(
            200,
            $result['@metadata']['statusCode']
        );

        $this->assertNotEmpty($authenticationResult['AccessToken']);
        $this->assertNotEmpty($authenticationResult['RefreshToken']);
        $this->assertNotEmpty($authenticationResult['IdToken']);
    }

    /**
     * @test
     */
    public function shouldRefreshToken(): void
    {

        $loginResult = $this->userAuthenticationService->loginUser(
            self::USERNAME,
            self::PASSWORD
        );

        $authenticationResult = $loginResult['AuthenticationResult'];

        $refreshTokenResult = $this->userAuthenticationService->refreshToken(
            self::USERNAME,
            $authenticationResult['RefreshToken']
        );

        $this->assertEquals(
            200,
            $refreshTokenResult['@metadata']['statusCode']
        );

        $this->assertNotEmpty($refreshTokenResult['AuthenticationResult']['AccessToken']);
        $this->assertNotEmpty($refreshTokenResult['AuthenticationResult']['IdToken']);
    }

    /**
     * @test
     */
    public function shouldChangePassword(): void
    {
        $expectedNewPassword = 'SomeNewPassword!123';

        $loginResult = $this->userAuthenticationService->loginUser(
            self::USERNAME,
            self::PASSWORD
        );

        $authenticationResult = $loginResult['AuthenticationResult'];

        $result = $this->userAuthenticationService->changePassword(
            $authenticationResult['AccessToken'],
            new Password(self::PASSWORD),
            new Password($expectedNewPassword)
        );

        $this->assertEquals(
            200,
            $result['@metadata']['statusCode']
        );

        $loginResultAfterPasswordChanged = $this->userAuthenticationService
            ->loginUser(self::USERNAME, $expectedNewPassword);

        $this->assertEquals(
            200,
            $loginResultAfterPasswordChanged['@metadata']['statusCode']
        );
    }

    private function createUser()
    {
        $user = new User(new Username(self::USERNAME));
        $password = new Password(self::PASSWORD);

        $result = $this->userAuthenticationService->signUpUser($user, $password);

        $this->userAuthenticationService->adminConfirmSignUp(self::USERNAME);

        $this->userId = $result['UserSub'];
    }
}
