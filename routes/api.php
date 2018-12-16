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
        '/advertiser/{aid}/report',
        'AdvertiserController@report'
    );

    Route::get(
        '/advertiser/{aid}/report/line',
        'AdvertiserController@reportByLine'
    );

    Route::get(
        '/advertiser/{aid}/report/summary',
        'AdvertiserController@reportSummary'
    );
});
