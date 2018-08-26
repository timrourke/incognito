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
        $tokenConverter = TestUtility::getTokenConverter();

        $subject = new ClaimsValidator(
            $claimChecker,
            $headerChecker,
            $tokenConverter,
            new JWSTokenSupport()
        );

        $this->assertInstanceOf(
            ClaimsValidator::class,
            $subject
        );
    }

    public function testAreTokenClaimsValid(): void
    {
        $token = TestUtility::getJWS();

        $claimChecker = TestUtility::getClaimChecker();
        $headerChecker = TestUtility::getHeaderChecker();
        $tokenConverter = TestUtility::getTokenConverter();

        $subject = new ClaimsValidator(
            $claimChecker,
            $headerChecker,
            $tokenConverter,
            new JWSTokenSupport()
        );

        $tokenClaimsAreValid = $subject->validate($token);

        $this->assertTrue($tokenClaimsAreValid);
    }
}
