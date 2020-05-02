<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Entity\UserAttribute;

use Assert\AssertionFailedException;
use Incognito\Entity\UserAttribute\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new Email('some@email.com');

        static::assertInstanceOf(
            Email::class,
            $sut
        );
    }

    public function testConstructThrowsWithInvalidEmail(): void
    {
        static::expectException(AssertionFailedException::class);
        static::expectExceptionMessage(
            'Invalid email: "foo" must be a valid email address.'
        );

        new Email('foo');
    }
}
