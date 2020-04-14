<?php
namespace Api\Extra;

use Api\Extra\CacheTokenStorage;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;

class FacebookBusinessService
{
    /**
     * @var \FacebookAds\Api
     */
    public $api;

    /**
     * Initialize an instance of FacebookBusinessService
     */
    public function __construct()
    {
        $app_id     = config('admin.fbbus.app_id');
        $app_secret = config('admin.fbbus.app_secret');
        $app_token  = config('admin.fbbus.app_token');
        $storage    = new CacheTokenStorage();

        \FacebookAds\Api::init($app_id, $app_secret, $app_token);
        $this->api = \FacebookAds\Api::instance();
    }

    /**
     * @param $accountId
     */
    public function getAccount($accountId)
    {
        return new \FacebookAds\Object\AdAccount('act_' . $accountId);
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
