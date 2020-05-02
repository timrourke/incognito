<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Entity;

use Assert\AssertionFailedException;
use Incognito\Entity\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new Password('SomePassword123!');

        static::assertInstanceOf(
            Password::class,
            $sut
        );
    }

    public function testConstructThrowsWhenPasswordTooShort(): void
    {
        static::expectException(AssertionFailedException::class);
        static::expectExceptionMessage(
            'Invalid password: password must be between 8 and 256 characters in length.'
        );

        new Password('');
    }

    public function testConstructThrowsWhenPasswordTooLong(): void
    {
        $longPassword = '123!Someincrediblylongpasswordthatshouldabsolutelythrowanexceptionblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblahblah';
        static::expectException(AssertionFailedException::class);
        static::expectExceptionMessage(
            'Invalid password: password must be between 8 and 256 characters in length.'
        );

        new Password($longPassword);
    }

    public function testConstructThrowsWhenPasswordIsWeak(): void
    {
        static::expectException(AssertionFailedException::class);
        static::expectExceptionMessage(
            'Invalid password: password must contain uppercase and lowercase letters, numbers, and special characters.'
        );

        new Password('weakpassword');
    }

    public function testPassword(): void
    {
        $sut = new Password('charlesInCharge49?');

        static::assertEquals(
            'charlesInCharge49?',
            $sut->password()
        );
    }

    public function testToString(): void
    {
        $sut = new Password('kateyPass32!');

        static::assertEquals(
            'kateyPass32!',
            sprintf("%s", $sut)
        );
    }
}
