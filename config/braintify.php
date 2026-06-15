<?php

return [
    'name' => env('APP_NAME', 'Braintify'),
    'tagline' => 'Learn Fast. Teach Smart. Grow Together.',

    // Currency - Indonesia
    'currency' => 'IDR',
    'currency_symbol' => 'Rp',

    // Platform settings
    'platform_fee_percent' => 10, // 10% untuk mahasiswa
    'min_booking_price' => 20000,     // Rp 20.000
    'max_booking_price' => 100000,   // Rp 100.000
    'default_session_duration' => 30,

    // Skill exchange - GRATIS
    'min_exchange_duration' => 15,
    'max_exchange_duration' => 60,
    'default_exchange_duration' => 30,

    // Services
    'min_service_price' => 25000,      // Rp 25.000
    'max_service_delivery_days' => 14,
    'max_revisions' => 3,

    'per_page' => 12,
];