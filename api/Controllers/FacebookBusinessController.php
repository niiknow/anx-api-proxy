<?php
namespace Api\Controllers;

use Api\Controllers\Controller as MyController;
use Api\Extra\FacebookBusinessService;
use Api\Extra\ReportKeyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FacebookBusinessController extends MyController
{
    use ReportKeyTrait;

    /**
     * @var mixed
     */
    protected $svc;

    /**
     * Initialize an instance of ProxyController
     *
     * @param FacebookBusinessService $svc the service
     */
    public function __construct(FacebookBusinessService $svc)
    {
        $this->svc = $svc;
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

        // \Log::info($columns);
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

        if ($aid === 3678438 || $aid === 'advertiser_id') {
            $aid = 3195595760469797;
        }

        $params = [
            'level'          => 'campaign',
            'limit'          => 999,
            'filtering'      => [],
            'time_increment' => 1,
            'time_range'     => [
                'since' => $start_date->format('Y-m-d'),
                'until' => $end_date->format('Y-m-d')
            ]
        ];

        $account  = $this->svc->getAccount($aid);
        $insights = $account->getInsights($columns, $params);
        $data     = $this->transform($insights);

        return response()
            ->json([
                'advertiser_id' => $aid,
                'req'           => $params,
                'data'          => $data,
                'recordsTotal'  => count($data)
            ])
            ->header('Cache-Control', 'max-age=86400, public');
    }

    /**
     * @param  Request $request
     * @param  $aid
     * @return mixed
     */
    public function reportByCampaign(Request $request, $aid)
    {
        return $this->doReport($request, $aid, [
            'campaign_id',
            'campaign_name',
            'cpm',
            'cpc',
            'ctr',
            'impressions',
            'clicks',
            'spend'
        ]);
    }

    /**
     * @param $insights
     */
    protected function transform($insights)
    {
        //$myInsight = json_decode(json_encode($insights->getResponse()->getContent()['data']));

        $data = $insights->getResponse()->getContent()['data'];

        $rows   = [];
        $ignore = ['date_start', 'date_stop'];
        foreach ($data as $row) {
            // massage the row
            $newRow = ['day' => str_replace('-', '', $row['date_start'])];

            foreach ($row as $key => $v) {
                $k = trim(preg_replace('/\s+/', ' ', $key));
                if (in_array($k, $ignore, true)) {
                    // skip
                    continue;
                } else {
                    $newRow[$k] = $v;
                }
            }

            $rows[] = $newRow;
        }

        return $rows;
    }
}
