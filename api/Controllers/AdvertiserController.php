<?php

namespace Api\Controllers;

use Api\Controllers\Controller;
use Api\Extra\AppNexusService;
use Api\Extra\ReportKeyTrait;
use App\Exceptions\GeneralException;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdvertiserController extends Controller
{
    use ReportKeyTrait;

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

    public function reportByLine(Request $request, $aid)
    {
        return $this->doReport($request, $aid, [
            'line_item_id',
            'line_item_name',
            'day',
            'insertion_order_id',
            'insertion_order_name',
            'campaign_id',
            'campaign_name',
            'advertiser_id',
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

    public function reportSummary(Request $request, $aid)
    {
        return $this->doReport($request, $aid, [
            'day',
            'advertiser_id',
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

    public function report(Request $request, $aid)
    {
        return $this->doReport($request, $aid, explode(",", $request->query('columns')));
    }

    public function doReport(Request $request, $aid, $columns)
    {
        if (!$this->isValidKey($request->query('key'))) {
            return response()->json(['error' => 'Not authorized'], 403);
        }

        $reportId = $request->query('report_id');
        if (!isset($reportId)) {
            // select from report folder
            $param = [
                'report_type' => 'advertiser_analytics',
                'columns' => $columns,
                'time_granularity' => 'daily',
                'format' => 'csv',
                'timezone' => 'UTC',
                'report_interval' => 'last_30_days'
            ];

            $reportInterval = $request->query('report_interval');
            if (isset($reportInterval)) {
                $params['report_interval'] = $reportInterval;
            } else {
                $start = $request->query('start');
                if (isset($start)) {
                    unset($param['report_interval']);
                    $param['start_date'] = $start;

                    $end = $request->query('end');
                    if (isset($end)) {
                        $param['end_date'] = $end . ' 23:59:59';
                    } else {
                        $param['end_date'] = Carbon::now()->subDays(1)->format('Y-m-d') . ' 23:59:59';
                    }
                    \Log::info($param);
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

        $data   = array_map("str_getcsv", explode("\n", $pd));
        $header = array_shift($data);
        $hc     = count($header);
        $csv    = array();
        foreach ($data as $row) {
            if (count($row) == $hc) {
                $csv[] = array_combine($header, $row);
            }
        }

        return response()
            ->json([
                'id' => $reportId,
                'req' => $param,
                'recordsTotal' => count($csv),
                'data' => $csv
            ])
            ->header('Cache-Control', 'max-age=86400, public');
    }
}
