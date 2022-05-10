<?php
namespace Api\Controllers;

use Api\Controllers\Controller;
use Api\Extra\BrickEventService;
use Api\Extra\ReportKeyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BrickEventController extends Controller
{
    use ReportKeyTrait;

    /**
     * @param Request    $request
     * @param $aid
     * @param $columns
     */
    public function doReport(Request $request, $aid)
    {
        if (!$this->isValidKey($request->query('key'))) {
            return response()->json(['error' => 'Not authorized'], 403);
        }

        if ($aid === 'advertiser_id') {
            $aid = 'brick';
        }

        $start_date = Carbon::now()->subDays(1);
        $end_date   = Carbon::now();

        $start = $request->query('start');
        if (isset($start)) {
            $start_date = Carbon::parse($start);
        }

        $end = $request->query('end');
        if (isset($end)) {
            $end_date = Carbon::parse($end);
        }

        $url  = 'https://console.brickinc.net/api/v1/urchinevent/query/';

        $query = [
            'x-api-key' => env('BRICK_API_KEY'),
            'x-tenant'  => $aid,
            'select'    => 'id,event_at,event_category,event_label,event_value,event_x,event_y,referer,page,utm_campaign,utm_source,utm_medium,utm_content,utm_term',
            'filter[]'  => 'event_at:bt:'.$start_date->format('Y-m-d').'|'.$end_date->format('Y-m-d'),
            'sort[]'    => 'event_at|asc',
            'limit'     => '9999'
        ];
        $response = Http::get('https://console.brickinc.net/api/v1/urchinevent/query/', $query);
        $result   = $response->json();

        return response()->json($result, $response->status());
    }

    /**
     * @OA\Get(
     *   path="/advertiser/{aid}/rawlog",
     *   tags={"advertiser"},
     *   summary="get advertiser report by line item",
     *   @OA\Parameter(
     *     name="aid",
     *     in="path",
     *     description="advertiser id",
     *     required=true,
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     style="form"
     *   ),
     *   @OA\Parameter(
     *     name="start",
     *     in="query",
     *     description="start date",
     *     required=true,
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     style="form"
     *   ),
     *   @OA\Parameter(
     *     name="end",
     *     in="query",
     *     description="end date default as yesterday",
     *     required=false,
     *     @OA\Schema(
     *       type="string"
     *     ),
     *     style="form"
     *   ),
     *   @OA\Parameter(
     *     name="key",
     *     in="query",
     *     description="report api key",
     *     required=false,
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
    public function rawlog(Request $request, $aid)
    {
        return $this->doReport($request, $aid);
    }
}
