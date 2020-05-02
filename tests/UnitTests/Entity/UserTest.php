<?php

namespace Incognito\UnitTests\Entity;

use Assert\AssertionFailedException;
use DateTimeImmutable;
use Incognito\Entity\User;
use Incognito\Entity\Username;
use Incognito\Entity\UserStatus;
use Incognito\Entity\UserAttribute\UserAttribute;
use Incognito\Entity\UserAttribute\UserAttributeCollection;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new User(
            new Username('foo')
        );

        static::assertInstanceOf(
            User::class,
            $sut
        );
    }

    public function testConstructWithUserAttrs(): void
    {
        $attrs = new UserAttributeCollection([
            new UserAttribute('email', 'someone@somewhere.com'),
        ]);

        $sut = new User(
            new Username('foo'),
            $attrs
        );

        static::assertEquals(
            $attrs->toArray(),
            $sut->getAttributes()
        );
    }

    public function testSetAttribute(): void
    {
        $newEmail = new UserAttribute('email', 'someotheremail@somewhere.com');

        $attrs = new UserAttributeCollection([
            new UserAttribute('email', 'someone@somewhere.com'),
        ]);

        $sut = new User(
            new Username('foo'),
            $attrs
        );

        $sut->setAttribute($newEmail);

        static::assertEquals(
            $newEmail,
            $sut->getAttribute('email')
        );
    }

    public function testSetAttributeWhenNoAttributesExist(): void
    {
        $newEmail = new UserAttribute('email', 'someotheremail@somewhere.com');

        $sut = new User(
            new Username('foo')
        );

        $sut->setAttribute($newEmail);

        static::assertEquals(
            $newEmail,
            $sut->getAttribute('email')
        );
    }

    public function testGetAttribute(): void
    {
        $givenName = new UserAttribute('given_name', 'Robin');

        $attrs = new UserAttributeCollection([
            $givenName
        ]);

        $sut = new User(
            new Username('foo'),
            $attrs
        );

        static::assertEquals(
            $givenName,
            $sut->getAttribute('given_name')
        );
    }

    public function testGetAttributes(): void
    {
        $attrs = new UserAttributeCollection([
            new UserAttribute('email', 'someone@somewhere.com'),
        ]);

        $sut = new User(
            new Username('foo'),
            $attrs
        );

        static::assertEquals(
            $attrs->toArray(),
            $sut->getAttributes()
        );
    }

    public function testGetAttributesWhenNoneExist(): void
    {
        $sut = new User(
            new Username('foo')
        );

        static::assertEquals(
            [],
            $sut->getAttributes()
        );
    }

    public function testId(): void
    {
        $sut = new User(
            new Username('foo')
        );

        static::assertEquals(
            '',
            $sut->id()
        );
    }

    public function testSetId(): void
    {
        $sut = new User(
            new Username('foo')
        );

        $sut->setId('some-id');

        static::assertEquals(
            'some-id',
            $sut->id()
        );
    }

    public function testUsername(): void
    {
        $sut = new User(
            new Username('foo')
        );

        static::assertEquals(
            'foo',
            $sut->username()
        );
    }

    public function testSetUsername(): void
    {
        $sut = new User(
            new Username('foo')
        );

        $sut->setUsername(
            new Username('bar')
        );

        static::assertEquals(
            'bar',
            $sut->username()
        );
    }

    public function testCreatedAt(): void
    {
        $sut = new User(
            new Username('foo')
        );

        static::assertEquals(
            null,
            $sut->createdAt()
        );
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function testSetCreatedAt(): void
    {
        $sut = new User(
            new Username('foo')
        );

        $date = new DateTimeImmutable('now');

        $sut->setCreatedAt($date);

        static::assertEquals(
            $date,
            $sut->createdAt()
        );
    }

    public function testSetCreatedAtThrowsWhenAlreadySet(): void
    {
        static::expectException(AssertionFailedException::class);
        static::expectExceptionMessage(
            'Invalid createdAt: user already has a "createdAt" date.'
        );

        $sut = new User(
            new Username('foo')
        );

        $date = new DateTimeImmutable('now');

        $sut->setCreatedAt($date);
        $sut->setCreatedAt($date);
    }

    public function testUpdatedAt(): void
    {
        $sut = new User(
            new Username('foo')
        );

        static::assertEquals(
            null,
            $sut->updatedAt()
        );
    }

    public function testSetUpdatedAt(): void
    {
        $sut = new User(
            new Username('foo')
        );

        $date = new DateTimeImmutable('now');

        $sut->setUpdatedAt($date);

        static::assertEquals(
            $date,
            $sut->updatedAt()
        );
    }

    public function testEnabled(): void
    {
        $sut = new User(
            new Username('foo')
        );

        static::assertEquals(
            null,
            $sut->enabled()
        );
    }

    public function testSetEnabled(): void
    {
        $sut = new User(
            new Username('foo')
        );

        $sut->setEnabled(true);

        static::assertEquals(
            true,
            $sut->enabled()
        );

        $sut->setEnabled(false);

        static::assertEquals(
            false,
            $sut->enabled()
        );
    }

    public function testStatus(): void
    {
        $sut = new User(
            new Username('foo')
        );

        static::assertEquals(
            'UNKNOWN',
            sprintf('%s', $sut->status())
        );
    }

    /**
     * @dataProvider statusProvider
     * @param string $statusString
     * @throws \Assert\AssertionFailedException
     */
    public function testSetStatus(string $statusString): void
    {
        $expected = new UserStatus($statusString);

        $sut = new User(
            new Username('foo')
        );

        $sut->setStatus($expected);

        static::assertEquals(
            $expected,
            $sut->status()
        );
    }

    public function statusProvider(): array
    {
        return [
            ['UNCONFIRMED'],
            ['CONFIRMED'],
            ['ARCHIVED'],
            ['COMPROMISED'],
            ['UNKNOWN'],
            ['RESET_REQUIRED'],
            ['FORCE_CHANGE_PASSWORD'],
        ];
    }
}
