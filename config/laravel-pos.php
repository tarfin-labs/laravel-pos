<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'currency' => env('LARAVEL_POS_CURRENCY', 949),
    'locale' => env('LARAVEL_POS_LOCALE','tr'),
    'banks' => [
        'ziraat' => [
            'base_url' => env('LARAVEL_POS_ZIRAAT_BASE_URL',''),
            'merchant_id' => env('LARAVEL_POS_ZIRAAT_MERCHANT_ID', ''),
            'store_key' => env('LARAVEL_POS_ZIRAAT_STORE_KEY', ''),
        ]
    ]
];
