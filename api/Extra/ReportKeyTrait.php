<?php

namespace Api\Extra;

trait ReportKeyTrait
{
    /**
     * @param $key
     */
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
