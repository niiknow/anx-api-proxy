<?php

namespace Api\Extra;

use F3\AppNexusClient\TokenStorage;
use Illuminate\Support\Facades\Cache;

/**
 * CacheTokenStorage.
 *
 * @uses TokenStorage
 */
class CacheTokenStorage implements TokenStorage
{
    /**
     * get token.
     *
     * @param  string         $username
     * @return string|false
     */
    public function get($username)
    {
        $key = 'CacheTokenStorage_'.$username;
        if (Cache::has($key)) {
            return Cache::get($key);
        }

        return false;
    }

    /**
     * set token.
     *
     * @param  string $username
     * @param  string $token
     * @return void
     */
    public function set($username, $token)
    {
        Cache::put('CacheTokenStorage_'.$username, $token, 59);
    }
}
