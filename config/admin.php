<?php

return [
    'api_keys'   => env('API_KEY'),
    'report_key' => env('REPORT_KEY'),
    'appnexus'   => [
        'username' => env('APPNEXUS_USERNAME'),
        'password' => env('APPNEXUS_PASSWORD'),
        'url'      => env('APPNEXUS_URL', 'https://api-test.appnexus.com')
    ]
];
