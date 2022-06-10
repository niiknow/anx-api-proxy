<?php

namespace Api\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="AppNexus Proxy API",
 *   description="A rest-ful API to proxy AppNexus.",
 *   @OA\Contact(
 *      email="friends@niiknow.org"
 *   ),
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
