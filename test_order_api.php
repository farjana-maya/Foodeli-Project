<?php

// Test order creation API
$url = 'http://127.0.0.1:8000/api/orders';

$data = [
    'restaurant_id' => 1,
    'items' => [
        [
            'menu_item_id' => 1,
            'quantity' => 2,
            'price' => 150.00
        ]
    ],
    'delivery_address' => 'Test Address, Dhaka',
    'customer_phone' => '01700000000',
    'subtotal' => 300.00,
    'delivery_fee' => 50.00,
    'total_amount' => 350.00
];

$options = [
    'http' => [
        'header' => [
            'Content-Type: application/json',
            'Authorization: Bearer test-token'
        ],
        'method' => 'POST',
        'content' => json_encode($data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "Error making request\n";
    print_r($http_response_header);
} else {
    echo "Response: " . $result . "\n";
}