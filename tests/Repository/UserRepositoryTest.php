<?php

declare(strict_types=1);

namespace Incognito\Repository;

use Aws\Command;
use Aws\Result;
use Aws\Exception\AwsException;
use Incognito\CognitoClient\Exception\UserNotFoundException;
use Incognito\CognitoClient\UserQueryService;
use Incognito\Entity\User;
use Incognito\Entity\Username;
use Incognito\Mapper\UserMapper;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new UserRepository(
            new UserMapper(),
            $this->getUserQueryServiceMock()
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

        $queryService = $this->getUserQueryServiceMock();

        $queryService->expects($this->once())
            ->method('getUserByUsername')
            ->with('some-username')
            ->willReturn($expectedResult);

        $mapper = $this->getUserMapperMock();

        $mapper->expects($this->once())
            ->method('mapAdminGetUserResult')
            ->with($expectedResult)
            ->willReturn($expectedUser);

        $sut = new UserRepository(
            $mapper,
            $queryService
        );

        $actual = $sut->find('some-username');

        $this->assertEquals(
            $expectedUser,
            $actual
        );
    }

    public function testFindThrowsGenericAwsException(): void
    {
        $this->expectException(AwsException::class);

        $awsException = new AwsException(
            'some-message',
            new Command('some-command')
        );

        $queryService = $this->getUserQueryServiceMock();

        $queryService->expects($this->once())
            ->method('getUserByUsername')
            ->with('some-username')
            ->willThrowException($awsException);

        $mapper = $this->getUserMapperMock();

        $sut = new UserRepository(
            $mapper,
            $queryService
        );

        $sut->find('some-username');
    }

    public function testFindThrowsUserNotFoundException(): void
    {
        $this->expectException(UserNotFoundException::class);

        $awsException = new AwsException(
            'some-message',
            new Command('some-command'),
            [
                'code' => 'UserNotFoundException'
            ]
        );

        $queryService = $this->getUserQueryServiceMock();

        $queryService->expects($this->once())
            ->method('getUserByUsername')
            ->with('some-username')
            ->willThrowException($awsException);

        $mapper = $this->getUserMapperMock();

        $sut = new UserRepository(
            $mapper,
            $queryService
        );

       $sut->find('some-username');
    }

    public function testFindAll(): void
    {
        $expectedResult = $this->getAwsResult();

        $queryService = $this->getUserQueryServiceMock();

        $queryService->expects($this->once())
            ->method('getList')
            ->willReturn($expectedResult);

        $mapper = $this->getUserMapperMock();

        $mapper->expects($this->once())
            ->method('mapListUsersResult')
            ->with($expectedResult)
            ->willReturn([]);

        $sut = new UserRepository(
            $mapper,
            $queryService
        );

        $actual = $sut->findAll();

        $this->assertEquals(
            [],
            $actual
        );
    }

    private function getUserQueryServiceMock()
    {
        return $this->getMockBuilder(UserQueryService::class)
            ->disableOriginalConstructor()
            ->getMock();
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
