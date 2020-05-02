<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Token\Validation;

use Incognito\Token\Validation\ClaimsValidator;
use Incognito\UnitTests\Token\TestUtility;
use Jose\Component\Signature\JWSTokenSupport;
use PHPUnit\Framework\TestCase;

class ClaimsValidatorTest extends TestCase
{
    public function testConstruct(): void
    {
        $claimChecker = TestUtility::getClaimChecker();
        $headerChecker = TestUtility::getHeaderChecker();

        $subject = new ClaimsValidator(
            $claimChecker,
            $headerChecker,
            new JWSTokenSupport()
        );

        static::assertInstanceOf(
            ClaimsValidator::class,
            $subject
        );
    }

    /**
     * @throws \Jose\Component\Checker\InvalidClaimException
     * @throws \Jose\Component\Checker\InvalidHeaderException
     * @throws \Jose\Component\Checker\MissingMandatoryClaimException
     * @throws \Jose\Component\Checker\MissingMandatoryHeaderParameterException
     */
    public function testAreTokenClaimsValid(): void
    {
        $token = TestUtility::getJWS();

        $claimChecker = TestUtility::getClaimChecker();
        $headerChecker = TestUtility::getHeaderChecker();

        $subject = new ClaimsValidator(
            $claimChecker,
            $headerChecker,
            new JWSTokenSupport()
        );

        $tokenClaimsAreValid = $subject->validate($token);

        static::assertTrue($tokenClaimsAreValid);
    }
}
