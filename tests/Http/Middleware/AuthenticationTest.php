<?php

namespace Incognito\Http\Middleware;

use Incognito\Http\InvalidTokenResponseFactory;
use Incognito\Token\Service;
use Incognito\Token\ServiceFactory;
use Incognito\Token\TestUtility;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{
    private function getTokenService(): Service
    {
        return ServiceFactory::make(
            TestUtility::EXPECTED_AUDIENCE,
            TestUtility::getKeyset()
        );
    }

    public function testConstruct(): void
    {
        $service = $this->getTokenService();
        $authErrorResponseFactory = new InvalidTokenResponseFactory();

        $sut = new Authentication(
            $service,
            $authErrorResponseFactory
        );

        $this->assertInstanceOf(
            Authentication::class,
            $sut
        );
    }
}
