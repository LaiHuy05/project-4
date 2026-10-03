<?php
require __DIR__ . '/../service/CheckoutService.php';
function test_checkout($pass, $name) { if (!$pass) throw new RuntimeException($name); }
test_checkout(CheckoutService::parseItemIds('2,5') === [2,5], 'parse selected cart item IDs');
foreach (['', '2,2', '2,abc', '0', '-1'] as $bad) {
    try {
        CheckoutService::parseItemIds($bad);
        throw new RuntimeException('accepted invalid cart IDs: ' . $bad);
    } catch (InvalidArgumentException $expected) {}
}
$s = CheckoutService::totals([
    ['sp_price' => '150000', 'cd_quantity' => 2],
    ['sp_price' => '200000', 'cd_quantity' => 1],
]);
test_checkout($s === ['total' => 500000, 'quantity' => 3], 'server priced totals');
test_checkout(CheckoutService::validateForm(['namePay' => 'A']) !== [], 'required fields');
echo "Checkout smoke tests passed\n";
