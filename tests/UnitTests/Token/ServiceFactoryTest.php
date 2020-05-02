<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Token;

use Incognito\Token\TokenValidator;
use Incognito\Token\TokenValidatorFactory;
use PHPUnit\Framework\TestCase;

class ServiceFactoryTest extends TestCase
{
    public function testMake(): void
    {
        $service = TokenValidatorFactory::make(
            'some-cognito-client-app-id',
            TestUtility::getKeyset()
        );

        static::assertInstanceOf(
            TokenValidator::class,
            $service
        );
    }
}
