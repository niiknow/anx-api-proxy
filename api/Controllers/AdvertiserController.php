<?php
namespace Api\Controllers;

use Api\Controllers\Controller;
use Api\Extra\AppNexusService;
use Api\Extra\ReportKeyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdvertiserController extends Controller
{
    use ReportKeyTrait;

    /**
     * @var mixed
     */
    protected $anx;

    /**
     * Initialize an instance of ProxyController
     *
     * @param AppNexusService $anx the AppNexus service
     */
    public function __construct(AppNexusService $anx)
    {
        $this->anx = $anx;
    }

    /**
     * @param Request    $request
     * @param $aid
     * @param $columns
     */
    public function doReport(Request $request, $aid, $columns)
    {
        if (!$this->isValidKey($request->query('key'))) {
            return response()->json(['error' => 'Not authorized'], 403);
        }

        // always include day column as first column
        $columns = array_diff($columns, ['day']);
        array_unshift($columns, 'day');
        \Log::info($columns);

        $reportId = $request->query('report_id');
        if (!isset($reportId)) {
            // select from report folder
            $param = [
                'report_type'      => 'advertiser_analytics',
                'columns'          => $columns,
                'time_granularity' => 'daily',
                'format'           => 'csv',
                'timezone'         => 'UTC',
                'report_interval'  => 'last_30_days',
                'end_date'         => Carbon::now()->subDays(1)->format('Y-m-d') . ' 23:59:59'
            ];

            $reportInterval = $request->query('report_interval');
            if (isset($reportInterval)) {
                unset($param['end_date']);
                $param['report_interval'] = $reportInterval;
            } else {
                $start = $request->query('start');
                if (isset($start)) {
                    unset($param['report_interval']);
                    $param['start_date'] = $start;

                    $end = $request->query('end');
                    if (isset($end)) {
                        $param['end_date'] = $end . ' 23:59:59';
                    }
                } else {
                    unset($param['end_date']);
                }
            }

            $path = 'report?advertiser_id=' . $aid;

            try {
                $rst = $this->anx->call('POST', $path, ['report' => $param]);
            } catch (\RuntimeException $re) {
                return response()->json(['error' => $re->getMessage()], 443);
            }

            $reportId = $rst->report_id;
        }

        // most of the time, report is pretty fast due to cache
        // sleep for 1 seconds
        sleep(1);

        // check status
        $path = 'report?id=' . $reportId;
        $pd   = $this->anx->call('GET', $path);

        // report is pending, wait for 5 seconds
        if ($pd->execution_status === 'pending') {
            sleep(5);
        }

        $path = 'report-download?id=' . $reportId;
        $pd   = $this->anx->call('GET', $path);

        $data   = array_map('str_getcsv', explode("\n", $pd));
        $header = array_shift($data);
        $hc     = count($header);
        $csv    = [];
        foreach ($data as $row) {
            if (count($row) === $hc) {
                $crow        = array_combine($header, $row);
                $crow['day'] = preg_replace('/\-+/', '', $crow['day']);
                $csv[]       = $crow;
            }
        }

        return response()
            ->json([
                'id'            => $reportId,
                'advertiser_id' => $aid,
                'req'           => $param,
                'recordsTotal'  => count($csv),
                'data'          => $csv
            ])
            ->header('Cache-Control', 'max-age=86400, public');
    }

    /**
     * @OA\Get(
     *   path="/advertiser/{aid}/report",
     *   tags={"advertiser"},
     *   summary="get advertiser report",
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
     *     name="columns[]",
     *     in="query",
     *     description="csv list of columns",
     *     required=true,
     *     @OA\Schema(
     *       type="array",
     *       @OA\Items(type="string")
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
    public function report(Request $request, $aid)
    {
        $columns = $request->query('columns');

        if (is_string($columns)) {
            $columns = explode(',', $columns);
        }

        return $this->doReport($request, $aid, $columns);
    }

    /**
     * @OA\Get(
     *   path="/advertiser/{aid}/report/line",
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
    public function reportByLine(Request $request, $aid)
    {
        return $this->doReport($request, $aid, [
            'line_item_id',
            'line_item_name',
            'insertion_order_id',
            'insertion_order_name',
            'campaign_id',
            'campaign_name',
            'imps',
            'imps_viewed',
            'clicks',
            'ctr',
            'total_convs',
            'imps_viewed',
            'view_measured_imps',
            'view_measurement_rate',
            'view_rate'
        ]);
    }

    /**
     * @OA\Get(
     *   path="/advertiser/{aid}/report/summary",
     *   tags={"advertiser"},
     *   summary="get advertiser report summary",
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
    public function reportSummary(Request $request, $aid)
    {
        return $this->doReport($request, $aid, [
            'imps',
            'imps_viewed',
            'clicks',
            'ctr',
            'total_convs',
            'imps_viewed',
            'view_measured_imps',
            'view_measurement_rate',
            'view_rate'
        ]);
    }
}
