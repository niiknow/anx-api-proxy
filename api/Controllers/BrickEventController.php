<?php
namespace Api\Controllers;

use Api\Controllers\Controller;
use Api\Extra\BrickEventService;
use Api\Extra\ReportKeyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BrickEventController extends Controller
{
    use ReportKeyTrait;

    /**
     * @var mixed
     */
    protected $svc;

    /**
     * Initialize an instance of BrickEventController
     *
     * @param BrickEventService $svc the BrickEvent service
     */
    public function __construct(BrickEventService $svc)
    {
        $this->svc = $svc;
    }

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

        // check status
        $path = 'report?id=' . $reportId;
        $pd   = $this->svc->call('GET', $path);


        return response()
            ->json([
                'recordsTotal'  => 1,
                'data'          => $pd
            ])
            ->header('Cache-Control', 'max-age=86400, public');
    }

    /**
     * @OA\Get(
     *   path="/advertiser/{aid}/rawlogs",
     *   tags={"advertiser"},
     *   summary="get advertiser report by line item",
     *   @OA\Parameter(
     *     name="aid",
     *     in="path",
     *     description="advertiser id",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
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
    public function rawlogs(Request $request, $aid)
    {
        return $this->doReport($request, $aid);
    }
}
