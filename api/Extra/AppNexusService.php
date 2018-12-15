<?php

namespace Api\Extra;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use F3\AppNexusClient\AppNexusClient;

use Api\Extra\CacheTokenStorage;

class AppNexusService
{
    /**
     * @var \F3\AppNexusClient\AppNexusClient
     */
    public $api;

    /**
     * Initialize an instance of AppNexusService
     */
    public function __construct()
    {
        $username = config('admin.appnexus.username');
        $password = config('admin.appnexus.password');
        $apiUrl   = config('admin.appnexus.url');
        $storage  = new CacheTokenStorage();
        $apiUrl   = trim($apiUrl, '/') . '/';

        $this->api = new AppNexusClient($username, $password, $apiUrl, $storage);
    }

    /**
     * Allow for unit test debugging.
     *
     * @return boolean
     */
    public function isDebug()
    {
        return false;
    }

    public function call($method, $path, array $post = array())
    {
        $rst = $this->api->call($method, $path, $post);
        return $rst;
    }
}
