<?php

declare(strict_types=1);

namespace Incognito\Token\ClaimsChecker;

use Jose\Component\Checker\ClaimChecker;
use Jose\Component\Checker\InvalidClaimException;

final class TokenUseChecker implements ClaimChecker
{
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function checkClaim($value)
    {
        if (empty($value)) {
            throw new InvalidClaimException(
                'Invalid claim "token_use". The claim "token_use" must have a value.',
                'token_use',
                $value
            );
        }

        if (!is_string($value)) {
            throw new InvalidClaimException(
                'Invalid claim "token_use". The claim "token_use" must be a string.',
                'token_use',
                $value
            );
        }

        if (!in_array($value, ['access', 'id'])) {
            throw new InvalidClaimException(
                'Invalid claim "token_use". The claim "token_use" must be "access" or "id".',
                'token_use',
                $value
            );
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supportedClaim(): string
    {
        return 'token_use';
    }
}