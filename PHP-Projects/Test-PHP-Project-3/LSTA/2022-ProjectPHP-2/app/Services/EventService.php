<?php

namespace App\Services;

use App\DatesInWeek;
use App\Event;
use App\ExcludedDate;
use App\MedicationPatient;
use App\MedicationPatientEvent;
use App\Questionnaire;
use App\User;
use Auth;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Validator;

class EventService
{

    /**
     * Converts a time and a date to a DateTime object
     *
     * @param $time
     * @param $date
     * @return DateTime
     * @throws Exception
     */
    public function convertToDateTime($time, $date): DateTime
    {
        $datetimeString = $date . ' ' . $time;
        return new DateTime($datetimeString);
    }

    public function convertTaskToFrontEnd($task) {
        $startDateTime = $task->start_date;
        if($task->duration != null) {
            $duration = $task->duration;
            $endDateTime = new Carbon($startDateTime);
            $endDateTime = $endDateTime->addMinutes($duration)->toDateTimeString();
        } else {
            $endDateTime = null;
        }

        $frontEndTask = clone($task);
        unset($frontEndTask->start_date);
        unset($frontEndTask->duration);
        $frontEndTask->start_date_time = $startDateTime;
        $frontEndTask->end_date_time = $endDateTime;

        return $frontEndTask;
    }

    public function qryTask($task) {

        $task['event_type_name'] = $task->eventType->name;
        unset($task->eventType);

        $frontEndTask = $this->convertTaskToFrontEnd($task);

        return $frontEndTask;

    }

    public function qryEvent($id) {
        $event = Event::find($id);
        if($event->eventType->name == 'medication' || $event->eventType->name == 'task') {
            $event = $this->qryTask($event);
        }

        return $event;
    }

    /**
     * Gets all the events that take place in the week of the given monday for the patient with this patient id
     * For repeating events it builds a list of dates on which this event will take place in that week
     * It adds the following attributes:
     * - Start Hour: Hour when the event starts
     * - Start Date: Date when the event will occur
     * - Is Repeating: True if the events repeats itself (has an interval and a time unit)
     *
     * @param $mondayString
     * @param $patientId
     * @return Builder[]|Collection
     * @uses \App\DatesInWeek
     */
    private function getWeeklyEvents($mondayString, $patientId)
    {
        $monday = Carbon::parse($mondayString);
        $sunday = $monday->clone()->addDays(6);

        return Event::with("timeUnit")
            ->with("eventType")
            ->with("excludedDates")
            ->where(function ($query) use ($monday, $sunday, $patientId) {
                $query->whereDate("end_date", ">=", $monday)
                    ->whereDate("start_date", "<=", $sunday)
                    ->where("patient_id", "=", $patientId);
            })
            ->orWhere(function ($query) use ($patientId, $monday, $sunday) {
                $query->whereDate("start_date", "<=", $sunday)
                    ->whereDate("start_date", ">=", $monday)
                    ->where("end_date", "=", null)
                    ->where("patient_id", "=", $patientId);
            })
            ->get()->transform(function ($value) use ($monday) {
                $timeUnitName = $value->timeUnit->name;
                if ($timeUnitName == null) {
                    $timeUnitName = "";
                }

                $excludedDateArray = [];
                foreach ($value->excludedDates as $excludedDate) {
                    $excludedDateArray[] = $excludedDate->date;
                }

                $value->excludedDates = $excludedDateArray;

                $datesInWeek = new DatesInWeek($monday, $value, $timeUnitName);
                $value->date_list = $datesInWeek->getDatesInWeek();
                unset($datesInWeek);

                $value->start_hour = Carbon::parse($value->start_date)->timezone('Europe/Amsterdam')->format("H:i:s");
                $value->start_date = Carbon::parse($value->start_date)->toDateString();

                $value->event_type_name = $value->eventType->name;

                return $value;
            });
    }

    public function getWeeklyEventsWithInfo($mondayString, $patientId): Collection
    {
        return $this->getWeeklyEvents($mondayString, $patientId)->makeHidden(['created_at', 'updated_at', 'timeUnit', 'patient_id', 'eventType']);
    }

    public function getWeeklyEventsWithoutInfo($mondayString, $patientId): Collection
    {
        return $this->getWeeklyEvents($mondayString, $patientId)->makeHidden(['confirmed', 'contact_person', 'created_at', 'interval', 'description', 'event_type', 'event_type_id', 'event_type_name', 'location', 'name', 'patient_id', 'questionnaire_id', 'reminder_sent_after', 'reminder_sent_at_time', 'reminder_sent_before', 'updated_at', 'time_unit', 'end_date', 'event_type', 'excluded_dates', 'time_unit_id'])
            ->transform(function ($value) {
                unset($value->excludedDates, $value->timeUnit, $value->eventType);
                return $value;
            });
    }

    /**
     * Stores an appointment to the database
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $currentEventDate = Carbon::parse($request->start_date);

        $durationArray = $this->getDurationArray($request->duration, $request->start_date);

        foreach ($durationArray as $duration) {
            $event = new Event($request->all());

            $event->start_date = $currentEventDate;
            $event->duration = $duration;

            $event->save();

            $currentEventDate->addDay()->setMinute(0)->setHour(0);
        }

        return $request->title;
    }

    private function getDurationArray(int $duration, string $startDate): array
    {
        $durationArray = [];

        $startDate = Carbon::parse($startDate);
        $endDate = $startDate->clone()->addMinutes($duration);

        if ($startDate->toDateString() != $endDate->toDateString()) {
            $startDateStartHour = $startDate->clone();
            $startDateEndHour = $startDateStartHour->clone()->setMinute(0)->setHour(24);

            $diffInMinutes = $startDateEndHour->diffInMinutes($startDateStartHour);

            $duration -= $diffInMinutes;
            $durationArray[] = $diffInMinutes;

            while ($duration > 1440) {
                $durationArray[] = 1440;
                $duration -= 1440;
            }
        }

        $durationArray[] = $duration;

        return $durationArray;
    }

    public function update(Request $request, Event $event) {
        $event->name = $request->name;
        $event->description = $request->description;
        $event->location = $request->location;
        $event->confirmed = false;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->duration = $request->duration;
        $event->interval = $request->interval;
        $event->contact_person = $request->contact_person;
        $event->patient_id = $request->patient_id;
        $event->time_unit_id = $request->time_unit_id;
        $event->questionnaire_id = $request->questionnaire_id;

        $event->save();
    }

    public function updateQuestionnaire(Request $request, Event $event) {
        $event->start_date = $request->start_date;

        $event->save();
    }

    public function createQuestionnaireEvent(Questionnaire  $questionnaire, Request $request){
        $event = new Event();
        $event->name = $questionnaire->name;
        $event->description = "Vragenlijst invullen";
        $event->start_date = $request->start_date;
        $event->patient_id = $questionnaire->patient_id;
        $event->questionnaire_id = $questionnaire->id;
        $event->event_type_id = 4;
        $event->duration = 5;

        if($request->interval){
            $event->interval = $request->interval;
            $event->end_date = $request->end_date;
            $event->time_unit_id = $request->time_unit_id;
        }


        $event->save();

    }

    public function confirmEvent(int $id) {
        $event = Event::findOrFail($id);

        $event->confirmed = true;
        $event->save();
    }

    public function excludeDate(Request $request) {
        $excludeDate = new ExcludedDate($request->all());
        $excludeDate->save();

        return $excludeDate->date;
    }

    public function validateRequest(Request $request): bool
    {
        return $request->duration > 0;
    }
}
