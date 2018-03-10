<?php

declare(strict_types=1);

namespace Incognito\Http;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class InvalidTokenResponseFactoryTest extends TestCase
{
    /**
     * @var array
     */
    private $expectedResponseBody = [
        'errors' => [
            0 => [
                'status' => '401',
                'title'  => 'Unauthorized',
                'detail' => 'The request has not been applied because it lacks valid authentication credentials for the target resource'
            ]
        ]
    ];

    public function testConstruct(): void
    {
        $sut = new InvalidTokenResponseFactory();

        $this->assertInstanceOf(
            InvalidTokenResponseFactory::class,
            $sut
        );
    }

    public function testCreateResponse(): void
    {
        $sut = new InvalidTokenResponseFactory();

        $response = $sut->createResponse();

        $this->assertInstanceOf(
            Response::class,
            $response
        );

        $this->assertEquals(401, $response->getStatusCode());

        $this->assertEquals(
            json_encode($this->expectedResponseBody),
            $response->getBody()
        );
    }
}