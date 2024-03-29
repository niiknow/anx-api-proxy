<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @param Request $request
     */
    public function healthCheck(Request $request)
    {
        // $output = exec('cd .. && ./webqueue.sh');
        // return $output;
        //
        return 'OK';
    }

    /**
     * @param Request $request
     */
    public function home(Request $request)
    {
        $data = [
            'csrfToken'    => csrf_token(),
            'env'          => config('app.env'),
            'api_endpoint' => config('app.api'),
            'appName'      => config('app.name'),
        ];

        return view('home', ['appSettings' => $data]);
    }
}
