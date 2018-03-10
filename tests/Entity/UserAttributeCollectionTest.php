<?php

namespace Incognito\Entity;

use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class UserAttributeCollectionTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new UserAttributeCollection();

        $this->assertInstanceOf(
            UserAttributeCollection::class,
            $sut
        );
    }

    public function testConstructThrowsWhenAllAttrsAreNotCorrectType(): void
    {
        $this->expectException(
            AssertionFailedException::class
        );

        $this->expectExceptionMessage(
           'Invalid user attributes: all elements in the array must be of type "\Incognito\Entity\UserAttribute".'
        );

        new UserAttributeCollection([
           'element of the incorrect type'
        ]);
    }

    public function testConstructThrowsWhenAttrsNotUniqueByName(): void
    {
        $nonUniqueArrayOfAttrs = [
            new UserAttribute('given_name', 'Charles'),
            new UserAttribute('given_name', 'Frederic'),
        ];

        $this->expectException(
            AssertionFailedException::class
        );

        $this->expectExceptionMessage(
            'Invalid user attributes: array of attributes must be unique by name. Non-unique attributes: given_name'
        );

        new UserAttributeCollection($nonUniqueArrayOfAttrs);
    }

    public function testAdd(): void
    {
        $expectedNewAttr = new UserAttribute('family_name', 'Smith');

        $sut = new UserAttributeCollection();

        $sut->add($expectedNewAttr);

        $this->assertEquals(
            [$expectedNewAttr],
            $sut->toArray()
        );
    }

    public function testAddReplacesExistingAttrOfSameName(): void
    {
        $originalGivenName = new UserAttribute('given_name', 'Rebecca');
        $familyName = new UserAttribute('family_name', 'Horton');
        $newGivenName = new UserAttribute('given_name', 'Rebekka');

        $sut = new UserAttributeCollection([
            $originalGivenName,
            $familyName,
        ]);

        $sut->add($newGivenName);

        $this->assertEquals(
            [
                $familyName,
                $newGivenName,
            ],
            $sut->toArray()
        );
    }

    public function testGet(): void
    {
        $givenName = new UserAttribute('given_name', 'Linda');
        $email = new UserAttribute('email', 'some@body.com');

        $sut = new UserAttributeCollection([
            $email,
            $givenName
        ]);

        $this->assertEquals(
            $email,
            $sut->get('email')
        );

        $this->assertEquals(
            $givenName,
            $sut->get('given_name')
        );

        $this->assertEquals(
            null,
            $sut->get('family_name')
        );
    }

    public function testToArray(): void
    {
        $expected = [
            new UserAttribute('email', 'soembody@nowhere.biz')
        ];

        $sut = new UserAttributeCollection($expected);

        $this->assertEquals(
            $expected,
            $sut->toArray()
        );
    }

    public function testToArrayReturnsAttrsInAlphabeticalOrder(): void
    {
        $first  = new UserAttribute('8');
        $second = new UserAttribute('AAA');
        $third  = new UserAttribute('cool yo');
        $last  = new UserAttribute('z');

        $sut = new UserAttributeCollection([
            $last,
            $third,
            $first,
            $second
        ]);

        $this->assertEquals(
            [
                $first,
                $second,
                $third,
                $last,
            ],
            $sut->toArray()
        );
    }

    public function testToArrayWhenEmpty(): void
    {
        $sut = new UserAttributeCollection();

        $this->assertEquals(
            [],
            $sut->toArray()
        );
    }
}
