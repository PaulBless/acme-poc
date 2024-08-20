# Acme Widget Co Sales System

This is a proof of concept for Acme Widget Co's new sales system, implemented in PHP. For simplicity, all Small accurate interfaces are designed in a singular class "Basket.php" file with a focus on maintainability and extensibility, making it easy to add new features or modify existing ones in the future.

## Task
We're tasked with creating a PHP class to represent a shopping cart for Acme Widget Co. This cart should:

1 Hold information about products, delivery costs, and offers.
2 Allow adding products to the cart.
3 Calculate the total cost of the cart considering delivery costs and offers.


## How it Works

The system allows adding products to a basket and calculates the total cost, including delivery charges and applicable offers. The delivery charges are reduced based on the total amount spent. A special offer of "buy one red widget, get the second half price" is applied when applicable.


### Key Features
1 **Product Catalog:** A predefined list of products.
2 **Delivery Charges:** Varying delivery charges based on order total.
3 **Special Offers:** Current offer: Buy one red widget, get the second half price.


### Assumptions
- Products and offers are passed into the basket at initialization.
- Delivery charges are applied based on the pre-discounted total.
- The system can easily be extended to add more products, offers, or change delivery rules.
- The various classes and interfaces could be organized into separate files, and be invoked or included correctly using file path or use autoloading with Composer.


## Usage

To use the system, initialize the basket with the product catalog, delivery rules, and offers. Then, add products using their product codes and get the total cost:

## Future Improvements
- Additional offers could be implemented to stack multiple discounts or handle more intricate rules.

- Add more flexible delivery rules based on region, product type, or customer status.

- Introduce logging to track operations, which would be beneficial in larger systems.


```php
$basket = new Basket($catalog, $deliveryCharges, $offers);
$basket->add('R01');
$basket->add('R01');
echo "Total: $" . $basket->total();

