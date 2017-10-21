<?php

namespace Warehouse\Domain\ObjectValues;

/**
 * Class Money
 * @package Warehouse\Domain
 */
class Money
{
    /**
     * @var int
     */
    private $amount;
    /**
     * @var string
     */
    private $currency;

    /**
     * Money constructor.
     * @param int $amount
     * @param $currency
     */
    public function __construct(int $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @param int $amount
     * @return Money
     */
    public static function USD(int $amount)
    {
        return new static($amount, 'USD');
    }
}