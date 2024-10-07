<?php

namespace App;

use App\Helpers\DateHelper;
use Carbon\Carbon;

class DatesInWeek
{
    private $unit;
    private $interval;
    private $startDate;
    private $endDate;
    private $monday;
    private $excludedDates;

    private $periodUntilMonday;
    private $firstDateAfterMonday;

    private $lowestEndDate;

    private $datesInWeek;

    public function __construct(Carbon $monday, Event $event, string $timeUnitName) {
        $this->unit = $timeUnitName;
        $this->interval = $event->interval;
        $this->excludedDates = $event->excludedDates;

        // The time isn't needed and would mess with the functionality of the functions
        // We set the time to 0
        $this->startDate = Carbon::parse($event->start_date)->setHour(0)->setMinute(0)->setSecond(0);
        $this->endDate = Carbon::parse($event->end_date)->setHour(0)->setMinute(0)->setSecond(0);

        $this->monday = $monday;

        // Only create a dates list if the event is repeating
        if ($this->_isRepeatingEvent()) {
            $this->_createDatesInWeek();
        } else {
            $this->datesInWeek = [];
        }
    }

    /**
     * Gives you the array of dates in the week when the event will take place
     *
     * @Return array
     */
    public function getDatesInWeek(): array
    {
        return $this->datesInWeek;
    }

    /**
     * Returns true if the event is a repeating event
     * A repeating event has a time unit and an interval
     * @return bool
     */
    private function _isRepeatingEvent(): bool
    {
        return $this->interval != null &&
            $this->unit != "";
    }

    /**
     * Returns true if the end date falls after the monday
     * @return bool
     */
    private function _isEndDateAfterMonday(): bool
    {
        $result = $this->endDate == null;

        if (!$result) {
            $result = $this->monday->lte($this->endDate);
        }

        return $result;
    }

    /**
     * Main function that sets the private variable datesInWeek
     * @return void
     */
    private function _createDatesInWeek() {
        // We only return a filled array if the monday is before or is the end date
        if ($this->_isEndDateAfterMonday()) {
            // The number of whole time units that are between the start date of the event and the monday of the week
            $this->periodUntilMonday = DateHelper::diffInUnit($this->startDate, $this->monday, $this->unit);

            // Sets the date on which the first event may be shown in our agenda
            // This can be the monday itself or less than one interval in the time unit after the monday
            $this->_setFirstDateAfterMonday();

            // Sets the lowest end date
            // This is the sunday of the week or the end date if it is before the sunday of that week
            $this->_setLowestEndDate();

            // Sets the array with the dates on which the events will take place
            $this->_setDatesInWeekArray();
        } else {
            $this->datesInWeek = [];
        }
    }

    /**
     * Sets the lowest end date
     * This is the sunday of the week or the end date if it is before the sunday of that week
     * @return void
     */
    private function _setLowestEndDate() {
        $sunday = $this->monday->clone()->addDays(6);
        if ($this->endDate == null) {
            $this->lowestEndDate = $sunday;
        } else {
            if ($sunday->lt($this->endDate)) {
                $this->lowestEndDate = $sunday;
            } else {
                $this->lowestEndDate = $this->endDate;
            }
        }
    }

    /**
     * Sets the first repetition after the given monday
     * This repetition will be in the unit of the event
     * @return void
     */
    private function _setFirstDateAfterMonday() {
        // Gets the last date before the selected monday
        // Always less than 1 full interval
        // Can be the same date as the monday itself (if periodUntilMonday can be integer divided by the interval)
        $lastPeriodBeforeDay = $this->periodUntilMonday - ($this->periodUntilMonday % $this->interval);

        // Create a date object from the last date before the day in the iteration of intervals after the start date
        $lastDateBeforeDay = DateHelper::addInUnit($this->startDate->clone(), $this->unit, $lastPeriodBeforeDay);


        // If the last date before the day is lower than (before) the day, we have to add one interval in the time unit
        // This will give you the next event date after the day we show in our agenda
        // If the last date before the day is equal to the day, we don't have to add an interval as we will show this day in our agenda
        if ($lastDateBeforeDay->clone()->setTime(0, 0)->lt($this->monday->clone()->setTime(0, 0))) {
            $this->firstDateAfterMonday = DateHelper::addInUnit($lastDateBeforeDay, $this->unit, $this->interval);
//            $this->datesInWeek = [$this->firstDateAfterMonday];
        } else {
            $this->firstDateAfterMonday = $this->startDate;
//            $this->datesInWeek = [];
        }
    }

    /**
     * Creates an array of the dates where te event will take place
     * These dates must be between the monday and the lowestEndDate (included)
     * @return void
     */
    private function _setDatesInWeekArray() {
        // If the first date after monday is more than one week after the monday,
        // there is no need to return it because it won't be shown in the current week of the agenda
        if ($this->firstDateAfterMonday->lte($this->lowestEndDate)) {
            // The list of date string that will be returned
            $datesInWeek = [];

            // The current date is the date we use to loop with
            // The initial value of this date is the same as the date of the first event after the monday
            $currentDate = $this->firstDateAfterMonday->clone();

            while ($currentDate->lte($this->lowestEndDate)) {
                // Add the date in string format to the array
                // Only dates that aren't excluded for this event are added
                $currentDateString = $currentDate->toDateString();
                if (!in_array($currentDateString, $this->excludedDates)) {
                    $datesInWeek[] = $currentDateString;
                }

                // Calculate the date the next event will take place
                // This is the date of the current event incremented by one interval times the time unit
                $currentDate = DateHelper::addInUnit($currentDate->clone() , $this->unit, $this->interval);
            }

            $this->datesInWeek = $datesInWeek;
        } else {
            $this->datesInWeek = [];
        }
    }
}
