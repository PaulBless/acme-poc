<?php

interface OfferStrategy
{
    /** 
    * offers promotion 
    * @param int $count, float $price
    * @return float
    */
    public function apply(int $count, float $price): float;
}

interface DeliveryChargeStrategy
{
    /**
     * get the delivery charge calculations based on total.
     * @param $subtotal
     * @return float
     */
    public function getCharge(float $subtotal): float;
}


class NoOffer implements OfferStrategy
{
    public function apply(int $count, float $price): float
    {
        return $count * $price;
    }
}

class RedWidgetOffer implements OfferStrategy
{
    public function apply(int $count, float $price): float
    {
        $fullPriceCount = intdiv($count, 2);
        $halfPriceCount = $count % 2;
        return ($fullPriceCount * 2 * $price) + ($halfPriceCount * $price * 0.5);
    }
}

class StandardDeliveryCharge implements DeliveryChargeStrategy
{
    private array $charges;

    public function __construct(array $charges)
    {
        $this->charges = $charges;
    }

    public function getCharge(float $subtotal): float
    {
        foreach ($this->charges as $limit => $charge) {
            if ($subtotal < $limit) {
                return $charge;
            }
        }
        return 0.0; // Free delivery for orders over the highest limit
    }
}

class Basket
{
    private array $catalog;
    private DeliveryChargeStrategy $deliveryChargeStrategy;
    private array $offerStrategies;
    private array $items = [];

    public function __construct(array $catalog, DeliveryChargeStrategy $deliveryChargeStrategy, array $offerStrategies = [])
    {
        $this->catalog = $catalog;
        $this->deliveryChargeStrategy = $deliveryChargeStrategy;
        $this->offerStrategies = $offerStrategies;
    }

    /**
     * add a product to the baskets.
     *
     * @return void
     */
    public function add(string $productCode): void
    {
        if (isset($this->catalog[$productCode])) {
            $this->items[] = $productCode;
        } else {
            throw new Exception("Product code {$productCode} not found in catalog.");
        }
    }

    /**
     * calculate total cost of products in the baskets.
     *
     * @return string
     */
    public function total(): string
    {
        $subtotal = 0.0;
        $productCount = array_count_values($this->items);

        foreach ($productCount as $productCode => $count) {
            $price = $this->catalog[$productCode]['price'];
            $offerStrategy = $this->offerStrategies[$productCode] ?? new NoOffer();
            $subtotal += $offerStrategy->apply($count, $price);
        }

        $deliveryCharge = $this->deliveryChargeStrategy->getCharge($subtotal);

        return number_format($subtotal + $deliveryCharge, 2);
    }
}


// Define product catalog: the three main products being sold
$catalog = [
    'R01' => ['name' => 'Red Widget', 'price' => 32.95],
    'G01' => ['name' => 'Green Widget', 'price' => 24.95],
    'B01' => ['name' => 'Blue Widget', 'price' => 7.95]
];


// Define delivery charge rules
$deliveryCharges = [
    50 => 4.95,
    90 => 2.95,
    PHP_INT_MAX => 0.0
];

// Create a delivery charge strategy
$deliveryChargeStrategy = new StandardDeliveryCharge($deliveryCharges);


// Define offer strategies
$offerStrategies = [
    'R01' => new RedWidgetOffer()
];


// Example baskets implementation
$basket1 = new Basket($catalog, $deliveryChargeStrategy, $offerStrategies);
$basket1->add('B01');
$basket1->add('G01');
echo "Basket 1 Total: $" . $basket1->total() . PHP_EOL; // Should be $37.85

$basket2 = new Basket($catalog, $deliveryChargeStrategy, $offerStrategies);
$basket2->add('R01');
$basket2->add('R01');
echo "Basket 2 Total: $" . $basket2->total() . PHP_EOL; // Should be $54.37

$basket3 = new Basket($catalog, $deliveryChargeStrategy, $offerStrategies);
$basket3->add('R01');
$basket3->add('G01');
echo "Basket 3 Total: $" . $basket3->total() . PHP_EOL; // Should be $60.85

$basket4 = new Basket($catalog, $deliveryChargeStrategy, $offerStrategies);
$basket4->add('B01');
$basket4->add('B01');
$basket4->add('R01');
$basket4->add('R01');
$basket4->add('R01');
echo "Basket 4 Total: $" . $basket4->total() . PHP_EOL; // Should be $98.27
