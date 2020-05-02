<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Entity;

use Assert\AssertionFailedException;
use Incognito\Entity\UserStatus;
use PHPUnit\Framework\TestCase;

class UserStatusTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new UserStatus('UNKNOWN');

        static::assertInstanceOf(
            UserStatus::class,
            $sut
        );
    }

    public function testConstructThrowsWhenProvidedInvalidStatus(): void
    {
        static::expectException(AssertionFailedException::class);
        static::expectExceptionMessage(
            'Invalid status: must provide a valid status, received: "blah"'
        );

        new UserStatus('blah');
    }

    public function testToString(): void
    {
        $sut = new UserStatus('FORCE_CHANGE_PASSWORD');

        static::assertEquals(
            'FORCE_CHANGE_PASSWORD',
            sprintf('%s', $sut)
        );
    }
}
