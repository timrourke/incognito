<?php

declare(strict_types=1);

namespace Incognito\Http\Middleware;

use Exception;
use Incognito\Http\ResponseFactoryInterface;
use Incognito\Token\TokenValidator;
use Jose\Component\Signature\JWS;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Authentication
 *
 * PSR-15 middleware for authenticating a request bearing an AWS Cognito JSON
 * Web Token
 *
 * @package Incognito\Http\Middleware
 */
class Authentication implements MiddlewareInterface
{
    /**
     * A regular expression for matching the compact serialized representation
     * of a JSON Web Token as an Authorization Bearer token
     *
     * @var string
     */
    private const AUTHORIZATION_BEARER_JWT_REGEXP = "/Bearer\s+(.*)$/i";

    /**
     * @var \Incognito\Http\ResponseFactoryInterface
     */
    private ResponseFactoryInterface $authErrorResponseFactory;

    /**
     * @var \Incognito\Token\TokenValidator
     */
    private TokenValidator $tokenService;

    /**
     * Constructor.
     *
     * @param \Incognito\Token\TokenValidator          $tokenService
     * @param \Incognito\Http\ResponseFactoryInterface $authErrorResponseFactory
     */
    public function __construct(
        TokenValidator $tokenService,
        ResponseFactoryInterface $authErrorResponseFactory
    ) {
        $this->tokenService             = $tokenService;
        $this->authErrorResponseFactory = $authErrorResponseFactory;
    }

    /**
     * Process an incoming server request for valid authentication via an AWS
     * Cognito JSON Web Token
     *
     * @param  ServerRequestInterface  $request
     * @param  RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            $this->authenticateRequest($request);

            return $handler->handle($request);
        } catch (Exception $e) {
            $handler->handle($request);

            return $this->authErrorResponseFactory->createResponse();
        }
    }

    /**
     * Authenticate a request
     *
     * @param  ServerRequestInterface $request
     * @return \Jose\Component\Signature\JWS
     * @throws \Exception
     */
    private function authenticateRequest(ServerRequestInterface $request): JWS
    {
        $jwtString = $this->fetchTokenFromRequest($request);

        return $this->tokenService->verifyToken($jwtString);
    }

    /**
     * Fetch the compact serialization form of a JSON Web Token from the
     * Authorization header
     *
     * @param  ServerRequestInterface $request
     * @return string
     */
    private function fetchTokenFromRequest(
        ServerRequestInterface $request
    ): string {
        $tokenMatches = [];

        // Attempt to fetch the Authorization header
        $authenticationHeader = $request->getHeader('Authorization');
        $header = isset($authenticationHeader[0]) ?
            $authenticationHeader[0] :
            '';

        // Attempt to match a JWT Bearer token from the Authorization header
        $didMatch = preg_match(
            self::AUTHORIZATION_BEARER_JWT_REGEXP,
            $header,
            $tokenMatches
        );

        return ($didMatch) ?
            $tokenMatches[1] :
            '';
    }
}
