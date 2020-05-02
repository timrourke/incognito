<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Mapper;

use Aws\Result;
use DateTimeImmutable;
use Incognito\Mapper\UserMapper;
use PHPUnit\Framework\TestCase;

/**
 * Class UserMapperTest
 * @package Incognito\UnitTests\Mapper
 */
class UserMapperTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new UserMapper();

        static::assertInstanceOf(
            UserMapper::class,
            $sut
        );
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function testMapAdminGetUserResult(): void
    {
        $sut = new UserMapper();

        $result = $this->buildAwsResultMock();

        $result->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'UserAttributes' => [
                    [
                        'Name' => 'sub',
                        'Value' => 'some-id'
                    ],
                    [
                        'Name' => 'given_name',
                        'Value' => 'Fred',
                    ],
                    [
                        'Name' => 'family_name',
                        'Value' => 'DiBenedetto',
                    ],
                    [
                        'Name' => 'email',
                        'Value' => 'fred.dib0@example.com',
                    ]
                ],
                'Enabled' => 'false',
                'Username' => 'some-username',
                'UserCreateDate' => '2017-01-02T01:02:03Z',
                'UserLastModifiedDate' => '2018-02-03T02:03:04Z',
                'UserStatus' => 'CONFIRMED',
            ]);

        $user = $sut->mapAdminGetUserResult($result);

        static::assertEquals(
            'some-id',
            $user->id()
        );

        static::assertEquals(
            new DateTimeImmutable('2017-01-02T01:02:03Z'),
            $user->createdAt()
        );

        static::assertEquals(
            new DateTimeImmutable('2018-02-03T02:03:04Z'),
            $user->updatedAt()
        );

        static::assertEquals(
            'CONFIRMED',
            $user->status()
        );

        static::assertEquals(
            false,
            $user->enabled()
        );

        static::assertEquals(
            'Fred',
            $user->getAttribute('given_name')->value()
        );

        static::assertEquals(
            'DiBenedetto',
            $user->getAttribute('family_name')->value()
        );

        static::assertEquals(
            'fred.dib0@example.com',
            $user->getAttribute('email')->value()
        );

        static::assertEquals(
            'some-id',
            $user->getAttribute('sub')->value()
        );
    }

    public function testMapListUsersResult(): void
    {
        $sut = new UserMapper();

        $result = $this->buildAwsResultMock();

        $result->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'Users' => [
                    0 => [
                        'Attributes' => [
                            [
                                'Name' => 'sub',
                                'Value' => 'some-id'
                            ],
                            [
                                'Name' => 'given_name',
                                'Value' => 'Fred',
                            ],
                            [
                                'Name' => 'family_name',
                                'Value' => 'DiBenedetto',
                            ],
                            [
                                'Name' => 'email',
                                'Value' => 'fred.dib0@example.com',
                            ]
                        ],
                        'Enabled' => 'false',
                        'Username' => 'some-username',
                        'UserCreateDate' => '2017-01-02T01:02:03Z',
                        'UserLastModifiedDate' => '2018-02-03T02:03:04Z',
                        'UserStatus' => 'CONFIRMED',
                    ],
                ],
            ]);

        $users = $sut->mapListUsersResult($result);

        static::assertEquals(
            'some-id',
            $users[0]->id()
        );

        static::assertEquals(
            new DateTimeImmutable('2017-01-02T01:02:03Z'),
            $users[0]->createdAt()
        );

        static::assertEquals(
            new DateTimeImmutable('2018-02-03T02:03:04Z'),
            $users[0]->updatedAt()
        );

        static::assertEquals(
            'CONFIRMED',
            $users[0]->status()
        );

        static::assertEquals(
            false,
            $users[0]->enabled()
        );

        static::assertEquals(
            'Fred',
            $users[0]->getAttribute('given_name')->value()
        );

        static::assertEquals(
            'DiBenedetto',
            $users[0]->getAttribute('family_name')->value()
        );

        static::assertEquals(
            'fred.dib0@example.com',
            $users[0]->getAttribute('email')->value()
        );

        static::assertEquals(
            'some-id',
            $users[0]->getAttribute('sub')->value()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|Result
     */
    private function buildAwsResultMock()
    {
        return $this->getMockBuilder(Result::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
