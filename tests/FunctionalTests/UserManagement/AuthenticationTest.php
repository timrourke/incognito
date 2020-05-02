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
     * @throws \Exception
     */
    public function shouldLogInUser(): void
    {
        $this->createUser();

        $result = $this->userAuthenticationService->loginUser(
            self::USERNAME,
            self::PASSWORD
        );

        $authenticationResult = $result['AuthenticationResult'];

        static::assertEquals(
            200,
            $result['@metadata']['statusCode']
        );

        static::assertNotEmpty($authenticationResult['AccessToken']);
        static::assertNotEmpty($authenticationResult['RefreshToken']);
        static::assertNotEmpty($authenticationResult['IdToken']);
    }

    /**
     * @test
     * @throws \Exception
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

        static::assertEquals(
            200,
            $refreshTokenResult['@metadata']['statusCode']
        );

        static::assertNotEmpty($refreshTokenResult['AuthenticationResult']['AccessToken']);
        static::assertNotEmpty($refreshTokenResult['AuthenticationResult']['IdToken']);
    }

    /**
     * @test
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
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

        static::assertEquals(
            200,
            $result['@metadata']['statusCode']
        );

        $loginResultAfterPasswordChanged = $this->userAuthenticationService
            ->loginUser(self::USERNAME, $expectedNewPassword);

        static::assertEquals(
            200,
            $loginResultAfterPasswordChanged['@metadata']['statusCode']
        );
    }

    /**
     * @throws \Exception
     */
    private function createUser()
    {
        $user = new User(new Username(self::USERNAME));
        $password = new Password(self::PASSWORD);

        $result = $this->userAuthenticationService->signUpUser($user, $password);

        $this->userAuthenticationService->adminConfirmSignUp(self::USERNAME);

        $this->userId = $result['UserSub'];
    }
}
