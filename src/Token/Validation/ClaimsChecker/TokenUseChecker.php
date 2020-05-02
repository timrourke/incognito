<?php

declare(strict_types=1);

namespace Incognito\Token\Validation\ClaimsChecker;

use Jose\Component\Checker\ClaimChecker;
use Jose\Component\Checker\InvalidClaimException;

/**
 * Class TokenUseChecker
 *
 * A claim checker that verifies the `token_use` claim's presence and value on a
 * JSON Web Token issued by your AWS Cognito User Pool
 *
 * @package Incognito\Token\Validation\ClaimsChecker
 */
final class TokenUseChecker implements ClaimChecker
{
    /**
     * @param mixed $value
     * @return bool
     * @throws \Jose\Component\Checker\InvalidClaimException
     */
    public function checkClaim($value): bool
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
