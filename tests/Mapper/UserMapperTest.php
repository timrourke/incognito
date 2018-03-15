<?php

declare(strict_types=1);

namespace Incognito\Mapper;

use Aws\Result;
use PHPUnit\Framework\TestCase;

class UserMapperTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new UserMapper();

        $this->assertInstanceOf(
            UserMapper::class,
            $sut
        );
    }

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

        $this->assertEquals(
            'some-id',
            $user->id()
        );

        $this->assertEquals(
            new \DateTimeImmutable('2017-01-02T01:02:03Z'),
            $user->createdAt()
        );

        $this->assertEquals(
            new \DateTimeImmutable('2018-02-03T02:03:04Z'),
            $user->updatedAt()
        );

        $this->assertEquals(
            'CONFIRMED',
            $user->status()
        );

        $this->assertEquals(
            false,
            $user->enabled()
        );

        $this->assertEquals(
            'Fred',
            $user->getAttribute('given_name')->value()
        );

        $this->assertEquals(
            'DiBenedetto',
            $user->getAttribute('family_name')->value()
        );

        $this->assertEquals(
            'fred.dib0@example.com',
            $user->getAttribute('email')->value()
        );

        $this->assertEquals(
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

        $this->assertEquals(
            'some-id',
            $users[0]->id()
        );

        $this->assertEquals(
            new \DateTimeImmutable('2017-01-02T01:02:03Z'),
            $users[0]->createdAt()
        );

        $this->assertEquals(
            new \DateTimeImmutable('2018-02-03T02:03:04Z'),
            $users[0]->updatedAt()
        );

        $this->assertEquals(
            'CONFIRMED',
            $users[0]->status()
        );

        $this->assertEquals(
            false,
            $users[0]->enabled()
        );

        $this->assertEquals(
            'Fred',
            $users[0]->getAttribute('given_name')->value()
        );

        $this->assertEquals(
            'DiBenedetto',
            $users[0]->getAttribute('family_name')->value()
        );

        $this->assertEquals(
            'fred.dib0@example.com',
            $users[0]->getAttribute('email')->value()
        );

        $this->assertEquals(
            'some-id',
            $users[0]->getAttribute('sub')->value()
        );
    }

    private function buildAwsResultMock()
    {
        return $this->getMockBuilder(Result::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
