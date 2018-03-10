<?php

declare(strict_types=1);

namespace Incognito\Entity;

use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class UserAttributeTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new UserAttribute('email', 'somebody@somewhere.biz');

        $this->assertInstanceOf(
            UserAttribute::class,
            $sut
        );
    }

    public function testConstructWithNoValue(): void
    {
        $sut = new UserAttribute('first_name');

        $this->assertInstanceOf(
            UserAttribute::class,
            $sut
        );
    }

    public function testConstructThrowsWhenNameNotLongEnough(): void
    {
        $this->expectException(
            AssertionFailedException::class
        );

        $this->expectExceptionMessage(
          'Invalid name "": name must be between 1 and 32 characters in length.'
        );

        new UserAttribute('');
    }

    public function testConstructThrowsWhenNameTooLong(): void
    {
        $this->expectException(
            AssertionFailedException::class
        );

        $this->expectExceptionMessage(
            'Invalid name "some name that is unacceptably long": name must be between 1 and 32 characters in length.'
        );

        new UserAttribute('some name that is unacceptably long');
    }

    public function testConstructThrowsWhenNameContainsInvalidCharacters(): void
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            "Invalid name \"\u{0003}\": name contains invalid characters."
        );

        new UserAttribute("\u{0003}");
    }

    public function testName(): void
    {
        $sut = new UserAttribute('given_name');

        $this->assertEquals(
            'given_name',
            $sut->name()
        );
    }

    public function testValue(): void
    {
        $sut = new UserAttribute('zoneinfo', 'America/Chicago');

        $this->assertEquals(
            'America/Chicago',
            $sut->value()
        );
    }
}
