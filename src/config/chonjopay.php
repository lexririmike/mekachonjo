<?php

return [
    /**
     * Route name of Chonjopay update handler
     */
    'update-route' => env('CHONJOPAY_UPDATE_ROUTE', 'chonjopay-update'),

    /**
     * Credentials
     */
    'api_key' => env('CHONJOPAY_API_KEY'),
    'secret' => env('CHONJOPAY_API_KEY_SECRET'),

    'hash' => env('CHONJOPAY_API_KEY_HASH', 'md5'),
    //'timestampHeader' => env('CHONJOPAY_API_KEY_TIMESTAMP_HEADER', 'X-Timestamp'),
    //'tokenHeader' => env('CHONJOPAY_API_KEY_TOKEN_HEADER', 'X-Authorization'),
    'window' => env('CHONJOPAY_API_KEY_WINDOW', 30),

    /**
     * Class bindings
     */
    'service' => Mekachonjo\Payment\Services\Chonjopay::class,
    'kernel' => \App\Chojnopay\Kernel::class,
];
