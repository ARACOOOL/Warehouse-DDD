<?php

namespace Warehouse\Domain\Customer\ObjectValues;

/**
 * Class Address
 * @package Warehouse\Domain
 */
final class Address
{
    /**
     * @var string
     */
    private $country;
    /**
     * @var string
     */
    private $city;
    /**
     * @var string
     */
    private $address;
    /**
     * @var int
     */
    private $zip;

    /**
     * Address constructor.
     * @param string $country
     * @param string $city
     * @param string $address
     * @param int $zip
     */
    public function __construct(string $country, string $city, string $address, int $zip)
    {
        $this->country = $country;
        $this->city = $city;
        $this->address = $address;
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getAddress() . ' ' . $this->getCity() . ' ' . $this->getZip() . ' ' . $this->getCountry();
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return int
     */
    public function getZip(): int
    {
        return $this->zip;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }
}