<?php

declare(strict_types=1);

namespace Incognito\Entity\UserAttribute;

use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new Email('some@email.com');

        $this->assertInstanceOf(
            Email::class,
            $sut
        );
    }

    public function testConstructThrowsWithInvalidEmail(): void
    {
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage(
            'Invalid email: "foo" must be a valid email address.'
        );

        new Email('foo');
    }
}
