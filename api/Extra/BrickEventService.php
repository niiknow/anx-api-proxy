<?php
namespace Api\Extra;

use Api\Extra\CacheTokenStorage;
use F3\AppNexusClient\AppNexusClient;

class BrickEventService
{
    /**
     * @var \F3\AppNexusClient\AppNexusClient
     */
    public $api;

    /**
     * Initialize an instance of BrickEventService
     */
    public function __construct()
    {
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
