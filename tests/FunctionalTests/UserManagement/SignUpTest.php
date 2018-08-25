<?php

declare(strict_types=1);

namespace Incognito\FunctionalTests\UserManagement;

use Incognito\Entity\Password;
use Incognito\Entity\User;
use Incognito\Entity\Username;
use Incognito\FunctionalTests\UserAuthenticationServiceFactory;
use PHPUnit\Framework\TestCase;

class SignUpTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSignUpUser(): void
    {
        $service = UserAuthenticationServiceFactory::build();

        $user = new User(new Username('Someonespecial'));

        $password = new Password('NewUserPassword123!');

        $result = $service->signUpUser($user, $password);

        $this->assertEquals(
            200,
            $result['@metadata']['statusCode']
        );

        $this->assertNotEmpty(
            $result['UserSub']
        );
    }
}