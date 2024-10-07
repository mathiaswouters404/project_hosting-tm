<?php

namespace App\Services\Reminders;

use App\Event;
use App\ExcludedDate;
use Carbon\Carbon;


class RepetitionService
{
    private $startDateTimeRepetition;
    private $endDateTimeRepetition;
    private $intervalAmount;
    private $intervalUnit;
    private $startDateTimePeriod;
    private $endDateTimePeriod;
    private $excludedDates;

    public function __construct($repeatingEvent, Carbon $startDateTimePeriod, Carbon $endDateTimePeriod) {
        $this->startDateTimeRepetition = $repeatingEvent['startDateTime'];
        if(array_key_exists("endDateTime", $repeatingEvent)) {
            $this->endDateTimeRepetition = $repeatingEvent['endDateTime'];
        }

        if(array_key_exists("excludedDates", $repeatingEvent)) {
            $this->excludedDates = $repeatingEvent['excludedDates'];
        }
        $this->intervalAmount = $repeatingEvent['intervalAmount'];
        $this->intervalUnit = $repeatingEvent['timeUnit'];

        $this->startDateTimePeriod = $startDateTimePeriod;
        $this->endDateTimePeriod = $endDateTimePeriod;
    }

    public static function newInstanceFromEvent(Event $event, Carbon $startDateTimePeriod, Carbon $endDateTimePeriod) {
        $excludedDatesQry = ExcludedDate::select('date')->where('event_id', $event->id)->get();

        $excludedDates = [];

        foreach($excludedDatesQry as $excludedDateQry) {
            array_push($excludedDates,
                Carbon::create($excludedDateQry->date)
            );
        }

        $repeatingEvent = [
            "startDateTime" => Carbon::create($event->start_date),
            "endDateTime" => $event->end_date == null ? null : Carbon::create($event->end_date),
            "intervalAmount" => $event->interval,
            "timeUnit" => $event->timeUnit->name
        ];

        if(count($excludedDates) > 0) {
            $repeatingEvent['excludedDates'] = $excludedDates;
        }


        return new RepetitionService($repeatingEvent, $startDateTimePeriod, $endDateTimePeriod);
    }

    public function getRepetitionsInPeriod() {

        $currentRepetition = $this->getLatestRepetition();


        $repetitions = [];


        while(($currentRepetition <= $this->endDateTimePeriod)
            and ($this->endDateTimeRepetition == null || $currentRepetition <= $this->endDateTimeRepetition)) {

            if($currentRepetition >= $this->startDateTimePeriod and
            !$this->isInExcludedDates($currentRepetition)) {
                array_push($repetitions, $currentRepetition->clone());
            }

            $this->addAmountInUnit(
                $currentRepetition,
                $this->intervalAmount,
                $this->intervalUnit
            );
        }

        return $repetitions;
    }

    public function getRepetitionsInPeriodFormatted() {
        return $this->formatDateTimeArray($this->getRepetitionsInPeriod());
    }

    private function getLatestRepetition() {
        $difference = $this->getDifferenceInUnit($this->startDateTimeRepetition, $this->startDateTimePeriod, $this->intervalUnit);


        if($difference > 0) {
            $amountOfRepetitionsSinceStart = intdiv($difference, $this->intervalAmount);
            $lastRepetitionDistanceToStart = $amountOfRepetitionsSinceStart*$this->intervalAmount;

            $latestRepetition = $this->addAmountInUnit(
                $this->startDateTimeRepetition->clone(),
                $lastRepetitionDistanceToStart,
                $this->intervalUnit);
        } else {
            $latestRepetition = $this->startDateTimeRepetition->clone();
        }

        return $latestRepetition;
    }

    private function getDifferenceInUnit(Carbon $dateTimeA, Carbon $dateTimeB, $timeUnit) {
        if($timeUnit == "days") {
            $difference = $dateTimeA->diffInDays($dateTimeB, false);
        }
        else if($timeUnit == "weeks") {
            $difference = $dateTimeA->diffInWeeks($dateTimeB, false);
        }
        else if($timeUnit == "months") {
            $difference = $dateTimeA->diffInMonths($dateTimeB, false);
        }
        else if($timeUnit == "years") {
            $difference = $dateTimeA->diffInYears($dateTimeB, false);
        }
        else if($timeUnit == "hours") {
            $difference = $dateTimeA->diffInHours($dateTimeB, false);
        }

        else if($timeUnit == "seconds") {
            $difference = $dateTimeA->diffInSeconds($dateTimeB, false);
        }

        return $difference;
    }

    private function addAmountInUnit(Carbon $dateTime, $amount, $timeUnit) {

        if($timeUnit == "days") {
            $newDateTime = $dateTime->addDays($amount);
        }
        else if($timeUnit == "weeks") {
            $newDateTime = $dateTime->addWeeks($amount);
        }
        else if($timeUnit == "months") {
            $newDateTime = $dateTime->addMonths($amount);
        }
        else if($timeUnit == "years") {
            $newDateTime = $dateTime->addYears($amount);
        }
        else if($timeUnit == "hours") {
            $newDateTime = $dateTime->addHours($amount);
        }
        else if($timeUnit == "seconds") {
            $newDateTime = $dateTime->addSeconds($amount);
        }

        return $newDateTime;
    }

    private function isInExcludedDates($dateTime) {

        if($this->excludedDates == null) {
            return false;
        }

        foreach($this->excludedDates as $excludedDate) {
            if($excludedDate->isSameDay($dateTime)) {
                return true;
            }
        }

        return false;
    }

    private function formatDateTime(Carbon $dateTime) {
        return $dateTime->format('Y-m-d H:i:s');
    }

    private function formatDateTimeArray($dateTimeArray) {
        $formattedArray = [];
        foreach($dateTimeArray as $dateTime) {
            array_push($formattedArray, $this->formatDateTime($dateTime));
        }
        return $formattedArray;
    }

}
