<?php

declare(strict_types=1);

namespace Incognito\Token;

use PHPUnit\Framework\TestCase;

class ServiceFactoryTest extends TestCase
{
    public function testMake(): void
    {
        $service = TokenValidatorFactory::make(
            'some-cognito-client-app-id',
            TestUtility::getKeyset()
        );

        $this->assertInstanceOf(
            TokenValidator::class,
            $service
        );
    }
}