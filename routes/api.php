<?php
use Niiknow\Laratt\LarattServiceProvider as r;

Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'proxy', 'middleware' => 'api'], function () {
        Route::match(
            ['get', 'post', 'put', 'delete'],
            '/{path?}',
            'ProxyController@index'
        )->where('path', '[\/\w\.-]*');
    });

    Route::get(
        '/advertiser/report',
        'AdvertiserController@report'
    );
});
