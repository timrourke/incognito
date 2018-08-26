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
     * @var string
     */
    private const STATUS_CODE = '401';

    /**
     * @var string
     */
    private const TITLE = 'Unauthorized';

    /**
     * @var string
     */
    private const DETAIL = <<<EOT
The request has not been applied because it lacks valid authentication credentials for the target resource
EOT;

    /**
     * JSON to render as the error response body
     *
     * @var array
     */
    private const ERROR_MESSAGE = [
        'errors' => [
            0 => [
                'status' => self::STATUS_CODE,
                'title'  => self::TITLE,
                'detail' => self::DETAIL,
            ],
        ],
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
            (string) json_encode(self::ERROR_MESSAGE)
        );
    }
}
