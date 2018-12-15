<?php

return [
    'api_key' => env('API_KEY'),
    'appnexus' => [
        'username' => env('APPNEXUS_USERNAME'),
        'password' => env('APPNEXUS_PASSWORD'),
        'url'      => env('APPNEXUS_URL', 'https://api-test.appnexus.com')
    ]
];
