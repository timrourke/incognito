<?php

declare(strict_types=1);

namespace Incognito\UnitTests\Token;

use Jose\Component\Signature\JWS;
use Incognito\Token\Deserializer;
use PHPUnit\Framework\TestCase;

class DeserializerTest extends TestCase
{
    public function testConstruct(): void
    {
        $serializerManager = TestUtility::getSerializerManager();

        $subject = new Deserializer($serializerManager);

        static::assertInstanceOf(
            Deserializer::class,
            $subject
        );
    }

    public function testGetTokenFromString(): void
    {
        $serializerManager = TestUtility::getSerializerManager();

        $subject = new Deserializer($serializerManager);

        $token = $subject->getTokenFromString('eyJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1MTk1MjE1NTYsIm5iZiI6MTUxOTUyMTU1NiwiZXhwIjoxNTE5NTI1MTU2LCJpc3MiOiJNeSBzZXJ2aWNlIiwiYXVkIjoiaHR0cHM6Ly9zb21lLWV4cGVjdGVkLWF1ZGllbmNlLmNvbSJ9.D_dn6BtAkkqs2gUV0KxwZZIQxNC7LZUSsK5BGiH4CCQQD9XG21srnONHk0p0H8qMhmzwUpKFzJNJAqVe6pY36IgE5IWF8stVbqAfdHIX2DvptuNEfRGQWt9lCo9uNhOs2E0HhmLFeZO_f26LrEKVlL2xhsCumGGjea7edLPa5sPRNOf19UHBD6-rwyEfIZuSsAAY2JQEnDyg_7AuXwVqry36fTQYRjNUyyx81oMJ9gup6h7zLdVX3mmlUs-38f1p_vCvgsbpVZCEcttR6ShAS5X7ywGjFSpdZjWjKqmeipnmRzBrNCSwsuoPhTJY6qmmqC7UklGizUEpTUkG8Tk1KT_XioxqKjrc4sL5q4jGZw6j3Q1AbfPuZiZdteGPuETNGIay7SBAhtc_3JWxFChsAgbdVRps9yEuSA1-BONPnHrSsCB6AoQVUtgyx4dqcl-Lw_bPeemvEWKoAlLTe0Ga1de2zWf31kr5PitKlxiljtKFf-H9g3ETSooch8bXz_qyJJaTZ2z65z8o4D_RjPSonIEWcGM4IrrZpLddl67yGmvYxyNhtHf6hXkr-zmFwXY07MCv1L4HVg1e6_5xXCYpmaBM2m6_K-2TrTAsQp_xNqxy8Zzp4JTMvQ_-S1CV3yFyXpNvZIaBtTSINh7G_G7hC81TwVSuxa2cmfNzmY3RjtU');

        static::assertInstanceOf(
            JWS::class,
            $token
        );
    }
}
