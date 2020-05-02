<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Http;

use GuzzleHttp\Psr7\Response;
use Incognito\Http\InvalidTokenResponseFactory;
use PHPUnit\Framework\TestCase;

class InvalidTokenResponseFactoryTest extends TestCase
{
    /**
     * @var array
     */
    private array $expectedResponseBody = [
        'errors' => [
            0 => [
                'status' => '401',
                'title' => 'Unauthorized',
                'detail' => 'The request has not been applied because it lacks valid authentication credentials for the target resource'
            ]
        ]
    ];

    public function testConstruct(): void
    {
        $sut = new InvalidTokenResponseFactory();

        static::assertInstanceOf(
            InvalidTokenResponseFactory::class,
            $sut
        );
    }

    public function testCreateResponse(): void
    {
        $sut = new InvalidTokenResponseFactory();

        $response = $sut->createResponse();

        static::assertInstanceOf(
            Response::class,
            $response
        );

        static::assertEquals(401, $response->getStatusCode());

        static::assertEquals(
            json_encode($this->expectedResponseBody),
            $response->getBody()
        );
    }
}
