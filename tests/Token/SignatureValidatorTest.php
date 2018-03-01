<?php

declare(strict_types=1);

namespace Incognito\Token;

use PHPUnit\Framework\TestCase;

class SignatureValidatorTest extends TestCase
{
    public function testConstruct()
    {
        $subject = new SignatureValidator(
            TestUtility::getKeyset(),
            TestUtility::getJwsVerifier()
        );

        $this->assertInstanceOf(
            SignatureValidator::class,
            $subject
        );
    }

    public function testIsTokenSignatureValid(): void
    {
        $token = TestUtility::getJWS();

        $subject = new SignatureValidator(
            TestUtility::getKeyset(),
            TestUtility::getJwsVerifier()
        );

        $actual = $subject->validate($token);

        $this->assertTrue($actual);
    }

}
