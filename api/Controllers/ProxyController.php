<?php

namespace Api\Controllers;

use Api\Extra\AppNexusService;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    /**
     * @var mixed
     */
    protected $anx;

    /**
     * Initialize an instance of ProxyController.
     *
     * @param AppNexusService $anx the AppNexus service
     */
    public function __construct(AppNexusService $anx)
    {
        $this->anx = $anx;
    }

    /**
     * @OA\Get(
     *   path="/proxy/{path}",
     *   tags={"proxy"},
     *   summary="get proxy",
     *   @OA\Parameter(
     *     name="X-API-Key",
     *     in="header",
     *     description="api key",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     style="form"
     *   ),
     *   @OA\Parameter(
     *     name="path",
     *     in="path",
     *     description="path",
     *     required=true,
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     style="form"
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="response object"
     *   )
     * )
     */

    /**
     * @OA\Post(
     *   path="/proxy/{path}",
     *   tags={"proxy"},
     *   summary="post proxy",
     *   @OA\Parameter(
     *     name="X-API-Key",
     *     in="header",
     *     description="api key",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     style="form"
     *   ),
     *   @OA\Parameter(
     *     name="path",
     *     in="path",
     *     description="path",
     *     required=true,
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     style="form"
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="response object"
     *   )
     * )
     */

    /**
     * @OA\Put(
     *   path="/proxy/{path}",
     *   tags={"proxy"},
     *   summary="put proxy",
     *   @OA\Parameter(
     *     name="X-API-Key",
     *     in="header",
     *     description="api key",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     style="form"
     *   ),
     *   @OA\Parameter(
     *     name="path",
     *     in="path",
     *     description="path",
     *     required=true,
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     style="form"
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="response object"
     *   )
     * )
     */

    /**
     * @OA\Delete(
     *   path="/proxy/{path}",
     *   tags={"proxy"},
     *   summary="delete proxy",
     *   @OA\Parameter(
     *     name="X-API-Key",
     *     in="header",
     *     description="api key",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     style="form"
     *   ),
     *   @OA\Parameter(
     *     name="path",
     *     in="path",
     *     description="path",
     *     required=true,
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     style="form"
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="response object"
     *   )
     * )
     */
    public function index(Request $request, $path)
    {
        $path = trim($path, '/');
        $path = $path.($request->getQueryString() ? ('?'.$request->getQueryString()) : '');

        try {
            $rst = $this->anx->call($request->getMethod(), $path, $request->all());
        } catch (\RuntimeException $re) {
            return response()->json(['message' => $re->getMessage()], 443);
        }

        return response()->json(['data' => $rst]);
    }
}
