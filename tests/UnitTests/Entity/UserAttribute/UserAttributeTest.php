<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Entity\UserAttribute;

use Assert\AssertionFailedException;
use Incognito\Entity\UserAttribute\UserAttribute;
use PHPUnit\Framework\TestCase;

class UserAttributeTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new UserAttribute('email', 'somebody@somewhere.biz');

        static::assertInstanceOf(
            UserAttribute::class,
            $sut
        );
    }

    public function testConstructWithNoValue(): void
    {
        $sut = new UserAttribute('first_name');

        static::assertInstanceOf(
            UserAttribute::class,
            $sut
        );
    }

    public function testConstructThrowsWhenNameNotLongEnough(): void
    {
        static::expectException(
            AssertionFailedException::class
        );

        static::expectExceptionMessage(
            'Invalid name "": name must be between 1 and 32 characters in length.'
        );

        new UserAttribute('');
    }

    public function testConstructThrowsWhenNameTooLong(): void
    {
        static::expectException(
            AssertionFailedException::class
        );

        static::expectExceptionMessage(
            'Invalid name "some name that is unacceptably long": name must be between 1 and 32 characters in length.'
        );

        new UserAttribute('some name that is unacceptably long');
    }

    public function testConstructThrowsWhenNameContainsInvalidCharacters(): void
    {
        static::expectException(AssertionFailedException::class);
        static::expectExceptionMessage(
            "Invalid name \"\u{0003}\": name contains invalid characters."
        );

        new UserAttribute("\u{0003}");
    }

    public function testName(): void
    {
        $sut = new UserAttribute('given_name');

        static::assertEquals(
            'given_name',
            $sut->name()
        );
    }

    public function testValue(): void
    {
        $sut = new UserAttribute('zoneinfo', 'America/Chicago');

        static::assertEquals(
            'America/Chicago',
            $sut->value()
        );
    }
}
