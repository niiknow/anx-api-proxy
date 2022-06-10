<?php

return [
    'api_keys'   => env('API_KEY'),
    'report_key' => env('REPORT_KEY'),
    'appnexus'   => [
        'username' => env('APPNEXUS_USERNAME'),
        'password' => env('APPNEXUS_PASSWORD'),
        'url'      => env('APPNEXUS_URL', 'https://api-test.appnexus.com'),
    ],
    'fbbus'      => [
        'app_id'     => env('FB_APP_ID'),
        'app_secret' => env('FB_APP_SECRET'),
        'app_token'  => env('FB_APP_TOKEN'),
    ],
];
