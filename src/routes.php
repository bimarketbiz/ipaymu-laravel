<?php

use Illuminate\Support\Facades\Route;
use Marketbiz\IPaymuLaravel\IPaymu;

Route::get('demo', function() {
// set buyer name
$iPaymu = new IPaymu();

$iPaymu->setBuyer([
    'name' => 'Bagus',
    'phone' => '08123123139',
    'email' => 'bagus@gmail.com',
]);

// set your reference id (optional)
$iPaymu->setReferenceId('123123');

// set your expiredPayment
$iPaymu->setExpired(24, 'hours'); // 24 hours

// set cod param (optional)
$iPaymu->setCOD([
    'deliveryArea' => "76111",
    'deliveryAddress' => "Denpasar",
]);

$carts = [];
$carts = $iPaymu->add(
    'PROD0001', // product id (string)
    'Jacket', // product name (string)
    12000, // price (float)
    2, // quantity (int)
    'Size M', // description
    1, // product weight (int) (optional)
    1, // product length (int) (optional)
    1, // product weight (int) (optional)
    1 // product height (int) (optional)
);
$carts = $iPaymu->add(
    'PROD0002', // product id (string)
    'Shoe', // product name (string)
    150000, // price (float)
    2, // quantity (int)
    'Size 8', // description
    1, // product weight (int) (optional)
    1, // product length (int) (optional)
    1, // product weight (int) (optional)
    1 // product height (int) (optional)
);

return $iPaymu->redirectPayment();
});