<?php

namespace Api\Extra;

use Illuminate\Http\Request;

trait ReportKeyTrait
{
    protected function isValidKey($key)
    {
        $myKey = config('admin.report_key', '');

        if (strlen($myKey) > 0) {
            if ($myKey !== $key) {
                return false;
            }
        }

        return true;
    }
}
