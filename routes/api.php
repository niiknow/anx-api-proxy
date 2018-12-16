<?php
use Niiknow\Laratt\LarattServiceProvider as r;

Route::group(['prefix' => 'v1', 'middleware' => 'api'], function () {
    Route::match(
        ['get', 'post', 'put', 'delete'],
        '/proxy/{path?}',
        'ProxyController@index'
    )->where('path', '[\/\w\.-]*');

    Route::get(
        '/advertiser/report',
        'AdvertiserController@report'
    );
});
