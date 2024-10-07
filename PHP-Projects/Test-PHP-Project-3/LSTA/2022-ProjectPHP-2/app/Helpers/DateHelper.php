<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Calculates the date after the start date that is incremented by the amount times the unit
     * @param Carbon $startDate the date we start our calculation from
     * @param string $unit in which unit we want to increment the start date
     * @param int $amount how many times the start date must be incremented by the unit
     * @return Carbon
     */
    public static function addInUnit(Carbon $startDate, string $unit, int $amount): Carbon
    {
        if($unit == 'days') {
            $startDate->addDays($amount);
        } else if($unit == 'weeks') {
            $startDate->addWeeks($amount);
        } else if($unit == 'months') {
            $startDate->addMonths($amount);
        } else if($unit == 'years') {
            $startDate->addYears($amount);
        }

        return $startDate;
    }

    /**
     * Returns the difference between two dates in the given unit
     * @param Carbon $startDate the first date
     * @param Carbon $endDate the last date
     * @param string $unit the unit in which you want the difference
     * @return int
     */
    public static function diffInUnit(Carbon $startDate, Carbon $endDate, string $unit): int
    {
        $difference = 0;

        if($unit == 'days') {
            $difference = $startDate->diffInDays($endDate);
        } else if($unit == 'weeks') {
            $difference = $startDate->diffInWeeks($endDate);
        } else if($unit == 'months') {
            $difference = $startDate->diffInMonths($endDate);
        } else if ($unit == 'years') {
            $difference = $startDate->diffInYears($endDate);
        }

        return $difference;
    }
}
