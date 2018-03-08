<?php

namespace Incognito\Entity;

use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class UsernameTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new Username('fredflinstone');

        $this->assertInstanceOf(
            Username::class,
            $sut
        );
    }

    public function testConstructThrowsWhenUsernameTooShort(): void
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            'Invalid username "": username must be between 1 and 128 characters in length.'
        );

        new Username('');
    }

    public function testConstructThrowsWhenUsernameTooLong(): void
    {
        $longUsername = 'someincrediblylongusernamethatshouldabsolutelythrowanexceptionblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblah';
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            'Invalid username "'. $longUsername .'": username must be between 1 and 128 characters in length.'
        );

        new Username($longUsername);
    }

    public function testConstructThrowsWhenUsernameUsesInvalidCharacters(): void
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            "Invalid username \"\u{0007}\": username contains invalid characters."
        );

        new Username("\u{0007}");
    }

    public function testUsername(): void
    {
        $sut = new Username('charles');

        $this->assertEquals(
            'charles',
            $sut->username()
        );
    }

    public function testToString(): void
    {
        $sut = new Username('katey32!');

        $this->assertEquals(
            sprintf("%s", $sut),
            'katey32!'
        );
    }
}
