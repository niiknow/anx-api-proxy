<?php

namespace Api\Extra\Middlewares;

use Closure;

class ValidApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiKey = config('admin.api_keys');
        if ($apiKey) {
            $apiKey = ','.preg_replace('/\s+/', '', $apiKey).',';

            $key = $request->header('x-api-key');
            if (strpos($apiKey, ','.$key.',') === false) {
                return response()->json(['error' => 'Not authorized'], 403);
            }
        }

        return $next($request);
    }
}
