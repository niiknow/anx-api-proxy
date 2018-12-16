<?php

namespace Api\Controllers;

use Api\Controllers\Controller;
use Api\Extra\AppNexusService;
use App\Exceptions\GeneralException;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdvertiserController extends Controller
{
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

    public function report(Request $request)
    {
        $reportId = $request->query('report_id');
        if (!isset($reportId)) {
            // select from report folder
            $param = [
                'report_type' => 'network_advertiser_analytics',
                'columns' => [
                    'line_item_id',
                    'day',
                    'line_item_name',
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
                ],
                'time_granularity' => 'daily',
                'format' => 'csv',
                'timezone' => 'UTC',

            ];

            $reportInterval = $request->query('report_interval');
            if (isset($reportInterval)) {
                $params['report_interval'] = $reportInterval;
            }

            $start = $request->query('start_date');
            if (isset($start)) {
                $param['start_date'] = $start;
            }

            $end = $request->query('end_date');
            if (isset($end)) {
                $param['end_date'] = $end . ' 23:59:59';
            }

            $path = 'report?advertiser_id=' . $request->query('advertiser_id');

            try {
                $rst = $this->anx->call('POST', $path, ['report' => $param]);
            } catch (\RuntimeException $re) {
                return response()->json(['message' => $re->getMessage()], 443);
            }

            $reportId = $rst->report_id;
        }

        // sleep for 3 seconds
        sleep(3);

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

        return response()->json(['data' => $csv, 'id' => $reportId]);
    }
}
