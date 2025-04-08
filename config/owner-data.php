<?php

return [
    'imprint' => [
        'name' => env('IMPRINT_NAME', 'Max Mustermann'),
        'address1' => env('IMPRINT_ADDRESS1', 'Musterstr. 22'),
        'address2' => env('IMPRINT_ADDRESS2', '12345 Musterstadt'),
        'country' => env('IMPRINT_COUNTRY', 'Germany'),
        'email' => env('IMPRINT_EMAIL', 'maxmustermann@gmail.com'),
        'phone' => env('IMPRINT_EMAIL', '+49 123 456789'),
    ],
    'privacy' => [
        'name' => env('PRIVACY_NAME', 'Max Mustermann'),
        'address1' => env('PRIVACY_ADDRESS1', 'Musterstr. 22'),
        'address2' => env('PRIVACY_ADDRESS2', '12345 Musterstadt'),
        'country' => env('PRIVACY_COUNTRY', 'Germany'),
        'email' => env('PRIVACY_EMAIL', 'maxmustermann@gmail.com'),
        'phone' => env('PRIVACY_EMAIL', '+49 123 456789'),
    ],
];
