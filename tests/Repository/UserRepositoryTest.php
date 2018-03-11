<?php

declare(strict_types=1);

namespace Incognito\Repository;

use Aws\Result;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient as CognitoClient;
use Incognito\CognitoClient\CognitoCredentials;
use Incognito\Entity\User;
use Incognito\Entity\Username;
use Incognito\Mapper\UserMapper;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new UserRepository(
            $this->getCognitoClientMock(),
            $this->getCognitoCredentials(),
            new UserMapper()
        );

        $this->assertInstanceOf(
            UserRepository::class,
            $sut
        );
    }

    public function testFind(): void
    {
        $expectedUser = new User(
            new Username('some-username')
        );

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

        $mapper = $this->getUserMapperMock();

        $mapper->expects($this->once())
            ->method('mapAdminGetUserResult')
            ->with($expectedResult)
            ->willReturn($expectedUser);

        $sut = new UserRepository(
            $client,
            $this->getCognitoCredentials(),
            $mapper
        );

        $actual = $sut->find('some-username');

        $this->assertEquals(
            $expectedUser,
            $actual
        );
    }

    public function testFindAll(): void
    {
        $expectedResult = $this->getAwsResult();

        $client = $this->getCognitoClientMock();

        $client->expects($this->once())
            ->method('__call')
            ->with('listUsers', [
                [
                    'UserPoolId' => 'someCognitoUserPoolId',
                ]
            ])
            ->willReturn($expectedResult);

        $mapper = $this->getUserMapperMock();

        $mapper->expects($this->once())
            ->method('mapListUsersResult')
            ->with($expectedResult)
            ->willReturn([]);

        $sut = new UserRepository(
            $client,
            $this->getCognitoCredentials(),
            $mapper
        );

        $actual = $sut->findAll();

        $this->assertEquals(
            [],
            $actual
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

    private function getUserMapperMock()
    {
        return $this->getMockBuilder(UserMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getAwsResult()
    {
        return $this->getMockBuilder(Result::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
