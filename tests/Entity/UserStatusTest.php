<?php

declare(strict_types=1);

namespace Incognito\Entity;

use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class UserStatusTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new UserStatus('UNKNOWN');

        $this->assertInstanceOf(
            UserStatus::class,
            $sut
        );
    }

    public function testConstructThrowsWhenProvidedInvalidStatus(): void
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            'Invalid status: must provide a valid status, received: "blah"'
        );

        new UserStatus('blah');
    }

    public function testToString(): void
    {
        $sut = new UserStatus('FORCE_CHANGE_PASSWORD');

        $this->assertEquals(
            'FORCE_CHANGE_PASSWORD',
            sprintf('%s', $sut)
        );
    }
}
