<?php

declare(strict_types=1);

namespace Incognito\Entity;

use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new Password('SomePassword123!');

        $this->assertInstanceOf(
            Password::class,
            $sut
        );
    }

    public function testConstructThrowsWhenPasswordTooShort(): void
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            'Invalid password: password must be between 8 and 256 characters in length.'
        );

        new Password('');
    }

    public function testConstructThrowsWhenPasswordTooLong(): void
    {
        $longPassword = '123!Someincrediblylongpasswordthatshouldabsolutelythrowanexceptionblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblah';
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            'Invalid password: password must be between 8 and 256 characters in length.'
        );

        new Password($longPassword);
    }

    public function testConstructThrowsWhenPasswordIsWeak(): void
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            'Invalid password: password must contain uppercase and lowercase letters, numbers, and special characters.'
        );

        new Password('weakpassword');
    }

    public function testPassword(): void
    {
        $sut = new Password('charlesInCharge49?');

        $this->assertEquals(
            'charlesInCharge49?',
            $sut->password()
        );
    }

    public function testToString(): void
    {
        $sut = new Password('kateyPass32!');

        $this->assertEquals(
            sprintf("%s", $sut),
            'kateyPass32!'
        );
    }
}
