<?php // @codeCoverageIgnore

namespace Incognito\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface ResponseFactoryInterface
 *
 * Useful for preventing tight coupling to a specific PSR-7 HTTP response object
 * implementation
 *
 * @package Incognito\Http
 * @codeCoverageIgnore
 */
interface ResponseFactoryInterface
{
    public function createResponse(): ResponseInterface;
}