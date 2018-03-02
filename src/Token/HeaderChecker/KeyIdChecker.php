<?php

declare(strict_types=1);

namespace Incognito\Token\HeaderChecker;

use Jose\Component\Checker\HeaderChecker;
use Jose\Component\Checker\InvalidHeaderException;

final class KeyIdChecker implements HeaderChecker
{
    /**
     * Validate the 'kid' header
     *
     * @see https://tools.ietf.org/html/rfc7515#section-4.1.4
     *
     * @param mixed $value Header value to validate
     * @return bool
     * @throws InvalidHeaderException
     */
    public function checkHeader($value): bool
    {
        if (empty($value)) {
            throw new InvalidHeaderException(
                'Invalid header "kid". "kid" must have a value.',
                'kid',
                $value
            );
        }

        if (!is_string($value)) {
            throw new InvalidHeaderException(
                'Invalid header "kid". "kid" must be a string.',
                'kid',
                $value
            );
        }

        return true;
    }

    /**
     * @return string
     */
    public function supportedHeader(): string
    {
        return 'kid';
    }

    /**
     * @return bool
     */
    public function protectedHeaderOnly(): bool
    {
        return true;
    }
}
