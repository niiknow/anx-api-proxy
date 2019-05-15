<?php
namespace Api\Extra;

use Api\Extra\CacheTokenStorage;
use F3\AppNexusClient\AppNexusClient;

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
     * @param  $method
     * @param  $path
     * @param  array     $post
     * @return mixed
     */
    public function call($method, $path, array $post = [])
    {
        $rst = $this->api->call($method, $path, $post);

        return $rst;
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
}
