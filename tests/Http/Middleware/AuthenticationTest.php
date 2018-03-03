<?php

namespace Incognito\Http\Middleware;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Incognito\Http\InvalidTokenResponseFactory;
use Incognito\Token\Service;
use Incognito\Token\ServiceFactory;
use Incognito\Token\TestUtility;
use Jose\Component\Signature\JWS;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * @var \Jose\Component\Signature\Serializer\JWSSerializerManager
     */
    private $serializer;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        $this->serializer = TestUtility::getSerializerManager();
    }

    public function testConstruct(): void
    {
        $sut = $this->getAuthentication();

        $this->assertInstanceOf(
            Authentication::class,
            $sut
        );
    }

    public function testProcess(): void
    {
        $sut = $this->getAuthentication();

        $validTokenString = $this->getValidTokenString();

        $authenticatedRequest = new ServerRequest(
            'GET',
            'localhost',
            [
                'Authorization' => 'Bearer ' . $validTokenString,
            ]
        );

        $handler = new PsrRequestHandlerStub();

        $response = $sut->process(
            $authenticatedRequest,
            $handler
        );

        $this->assertInstanceOf(
            Response::class,
            $response
        );

        $this->assertEquals(
            200,
            $response->getStatusCode()
        );
    }

    public function testProcessWhenUnauthenticated(): void
    {
        $sut = $this->getAuthentication();

        $unauthenticatedRequest = new ServerRequest(
            'GET',
            'localhost'
        );

        $handler = new PsrRequestHandlerStub();

        $response = $sut->process(
            $unauthenticatedRequest,
            $handler
        );

        $this->assertEquals(
            401,
            $response->getStatusCode()
        );
    }

    private function getTokenService(): Service
    {
        return ServiceFactory::make(
            TestUtility::EXPECTED_AUDIENCE,
            TestUtility::getKeyset()
        );
    }

    private function getAuthentication(): Authentication
    {
        $service = $this->getTokenService();
        $authErrorResponseFactory = new InvalidTokenResponseFactory();

        return new Authentication(
            $service,
            $authErrorResponseFactory
        );
    }

    private function getValidTokenString(): string
    {
        $token = TestUtility::getJWS();

        return $this->serializeToken($token);
    }

    private function serializeToken(JWS $token): string
    {
        return $this->serializer->serialize('jws_compact', $token);
    }
}
