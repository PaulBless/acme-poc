<?php

use PHPUnit\Framework\TestCase;

require_once 'Basket.php';          // Path to Basket class


class BasketTest extends TestCase
{
    private $catalog;
    private $deliveryCharges;
    private $offerStrategies;

    protected function setUp(): void
    {
        $this->catalog = [
            'R01' => ['name' => 'Red Widget', 'price' => 32.95],
            'G01' => ['name' => 'Green Widget', 'price' => 24.95],
            'B01' => ['name' => 'Blue Widget', 'price' => 7.95]
        ];

        $this->deliveryCharges = [
            50 => 4.95,
            90 => 2.95,
            PHP_INT_MAX => 0.0
        ];

        $this->offerStrategies = [
            'R01' => new RedWidgetOffer()
        ];
    }

    public function testBasketTotalWithB01AndG01()
    {
        $basket = new Basket($this->catalog, new StandardDeliveryCharge($this->deliveryCharges), $this->offerStrategies);
        $basket->add('B01');
        $basket->add('G01');

        $this->assertEquals('37.85', $basket->total());
    }

    public function testBasketTotalWithTwoR01()
    {
        $basket = new Basket($this->catalog, new StandardDeliveryCharge($this->deliveryCharges), $this->offerStrategies);
        $basket->add('R01');
        $basket->add('R01');

        $this->assertEquals('54.37', $basket->total());
    }

    public function testBasketTotalWithR01AndG01()
    {
        $basket = new Basket($this->catalog, new StandardDeliveryCharge($this->deliveryCharges), $this->offerStrategies);
        $basket->add('R01');
        $basket->add('G01');

        $this->assertEquals('60.85', $basket->total());
    }

    public function testBasketTotalWithThreeR01AndTwoB01()
    {
        $basket = new Basket($this->catalog, new StandardDeliveryCharge($this->deliveryCharges), $this->offerStrategies);
        $basket->add('B01');
        $basket->add('B01');
        $basket->add('R01');
        $basket->add('R01');
        $basket->add('R01');

        $this->assertEquals('98.27', $basket->total());
    }
}
