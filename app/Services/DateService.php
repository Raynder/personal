<?php

namespace App\Services;

use Carbon\Carbon;

class DateService
{
    public static function getIntervals(Carbon $startDate, Carbon $endDate): ?array
    {
        if (!DateService::isIntervalValid($startDate, $endDate)) {
            return null;
        }
        $dates = [];
        while ($startDate->isBefore($endDate)) {
            $dates[] = $startDate->clone();
            $startDate->addDay();
        }
        return $dates;
    }

    public static function isIntervalValid(Carbon $startDate, Carbon $endDate): bool
    {
        return $startDate->isBefore($endDate);
    }

    public static function getIntervalsInMonths(Carbon $startDate, Carbon $endDate): ?array
    {
        if (!DateService::isIntervalValid($startDate, $endDate)) {
            return null;
        }
        $dates = [];
        while ($startDate->isBefore($endDate)) {
            $dates[] = $startDate->clone();
            $startDate->addMonth();
        }
        return $dates;
    }
}
