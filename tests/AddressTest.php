<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Warehouse\Domain\Customer\ObjectValues\Address;

/**
 * Class AddressTest
 * @package tests
 */
class AddressTest extends TestCase
{
    /**
     *
     */
    public function testAddressGetters()
    {
        $address = new Address('US', 'Philadelphia, PA', '1815 Gerry Rw', 19118);
        self::assertEquals('1815 Gerry Rw', $address->getAddress());
        self::assertEquals('Philadelphia, PA', $address->getCity());
        self::assertEquals(19118, $address->getZip());
        self::assertEquals('US', $address->getCountry());
        self::assertEquals('1815 Gerry Rw Philadelphia, PA 19118 US', (string)$address);
    }
}