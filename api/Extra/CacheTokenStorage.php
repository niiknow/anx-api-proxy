<?php
namespace Api\Extra;

use Illuminate\Support\Facades\Cache;
use F3\AppNexusClient\TokenStorage;

/**
 * CacheTokenStorage
 *
 * @uses TokenStorage
 */
class CacheTokenStorage implements TokenStorage
{
    /**
     * set token
     *
     * @param string $username
     * @param string $token
     * @return void
     */
    public function set($username, $token)
    {
        Cache::put('CacheTokenStorage_' . $username, $token, 59);
    }

    /**
     * get token
     *
     * @param string $username
     * @return string|false
     */
    public function get($username)
    {
        $key = 'CacheTokenStorage_' . $username;
        if (Cache::has($key)) {
            return Cache::get($key);
        }

        return false;
    }
}
