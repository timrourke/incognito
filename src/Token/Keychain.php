<?php

declare(strict_types=1);

namespace Incognito\Token;

use GuzzleHttp\ClientInterface as HttpClientInterface;
use Incognito\Cache\CacheItemFactoryInterface;
use Jose\Component\Core\JWKSet;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class Keychain
 *
 * Fetches and caches the AWS Cognito public RSA keyset for verifying the
 * signature of JSON Web Tokens issued by your AWS Cognito service.
 *
 * @package Incognito\Token
 */
class Keychain
{
    /**
     * @var string
     */
    const JWT_KEYSET_CACHE_KEY = 'cognito.jwt.public-keys';

    /**
     * @var \Psr\Cache\CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var \Incognito\Cache\CacheItemFactoryInterface
     */
    private $cacheItemFactory;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $httpClient;

    /**
     * Constructor.
     *
     * @param \GuzzleHttp\ClientInterface                $httpClient
     * @param \Psr\Cache\CacheItemPoolInterface          $cache
     * @param \Incognito\Cache\CacheItemFactoryInterface $cacheItemFactory
     */
    public function __construct(
        HttpClientInterface $httpClient,
        CacheItemPoolInterface $cache,
        CacheItemFactoryInterface $cacheItemFactory
    ) {
        $this->httpClient = $httpClient;
        $this->cache      = $cache;
        $this->cacheItemFactory = $cacheItemFactory;
    }

    /**
     * Get a public keyset for this Cognito app.
     *
     * Attempts to fetch from cache first, and if cache is cold, makes an http
     * request to retrieve the keys from your Cognito app's public RSA keys
     * endpoint.
     *
     * @return \Jose\Component\Core\JWKSet
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getPublicKeyset(): JWKSet
    {
        $keyset = $this->getKeysetFromCache();

        if (!$keyset) {
            $keyset = $this->fetchAndCacheKeyset();
        }

        return $keyset;
    }

    /**
     * Make an http request for your Cognito app's public RSA keyset and store
     * it in the cache
     *
     * @return \Jose\Component\Core\JWKSet
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function fetchAndCacheKeyset(): JWKSet
    {
        $keysetData = $this->fetchKeysetData();

        $cacheItem = $this->cacheItemFactory->make(
            self::JWT_KEYSET_CACHE_KEY,
            $keysetData,
            true
        );

        $this->cache->save($cacheItem);

        return JWKSet::createFromKeyData($keysetData);
    }

    /**
     * Make an http request for your Cognito app's public RSA keyset
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function fetchKeysetData(): array
    {
        $keysResponse = $this->httpClient->request('GET', '');

        return json_decode($keysResponse->getBody()->getContents(), true);
    }

    /**
     * Try to get the RSA public keyset from the cache
     *
     * @return \Jose\Component\Core\JWKSet|null
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function getKeysetFromCache(): ?JWKSet
    {
        $keysetData = $this->cache->getItem(self::JWT_KEYSET_CACHE_KEY);

        return ($keysetData->get()) ?
            JWKSet::createFromKeyData($keysetData->get()) :
            null;
    }
}
