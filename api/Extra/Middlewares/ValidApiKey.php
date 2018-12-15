<?php
namespace Api\Extra\Middlewares;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class ValidApiKey
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiKey = config('admin.api_key');
        if ($apiKey) {
            $key = $request->header('x-api-key');
            if ($key != $apiKey) {
                return response()->json(['error' => 'Not authorized'], 403);
            }
        }

        return $next($request);
    }
}
