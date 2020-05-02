<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Entity;

use Assert\AssertionFailedException;
use Incognito\Entity\Username;
use PHPUnit\Framework\TestCase;

class UsernameTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new Username('fredflinstone');

        static::assertInstanceOf(
            Username::class,
            $sut
        );
    }

    public function testConstructThrowsWhenUsernameTooShort(): void
    {
        static::expectException(AssertionFailedException::class);
        static::expectExceptionMessage(
            'Invalid username "": username must be between 1 and 128 characters in length.'
        );

        new Username('');
    }

    public function testConstructThrowsWhenUsernameTooLong(): void
    {
        $longUsername = 'someincrediblylongusernamethatshouldabsolutelythrowanexceptionblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblah';
        static::expectException(AssertionFailedException::class);
        static::expectExceptionMessage(
            'Invalid username "'. $longUsername .'": username must be between 1 and 128 characters in length.'
        );

        new Username($longUsername);
    }

    public function testConstructThrowsWhenUsernameUsesInvalidCharacters(): void
    {
        static::expectException(AssertionFailedException::class);
        static::expectExceptionMessage(
            "Invalid username \"\u{0007}\": username contains invalid characters."
        );

        new Username("\u{0007}");
    }

    public function testUsername(): void
    {
        $sut = new Username('charles');

        static::assertEquals(
            'charles',
            $sut->username()
        );
    }

    public function testToString(): void
    {
        $sut = new Username('katey32!');

        static::assertEquals(
            'katey32!',
            sprintf("%s", $sut)
        );
    }
}
