<?php

declare(strict_types=1);

namespace Incognito\Http;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Class InvalidTokenResponse
 *
 * A factory useful for creating an HTTP response describing an authentication
 * error
 *
 * @package Incognito\Http
 */
class InvalidTokenResponseFactory implements ResponseFactoryInterface
{
    /**
     * JSON to render as the error response body
     *
     * @var array
     */
    private const ERROR_MESSAGE = [
        'errors' => [
            0 => [
                'status' => '401',
                'title'  => 'Unauthorized',
                'detail' => 'The request has not been applied because it lacks valid authentication credentials for the target resource'
            ]
        ]
    ];

    /**
     * Create an HTTP response describing an authentication error
     *
     * @return ResponseInterface
     */
    public function createResponse(): ResponseInterface
    {
        return new Response(
            401,
            [],
            json_encode(self::ERROR_MESSAGE)
        );
    }
}