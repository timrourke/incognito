<?php

namespace Incognito\Http;

use Psr\Http\Message\ResponseInterface;

interface ResponseFactoryInterface
{
    public function createResponse(): ResponseInterface;
}