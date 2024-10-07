<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        "name",
        "description",
        "location",
        "confirmed",
        "start_date",
        "end_date",
        "duration",
        "interval",
        "contact_person",
        "questionnaire_id",
        "patient_id",
        "time_unit_id",
        "event_type_id",
    ];


    public function timeUnit()
    {

        // An event has a time unit
        return $this->belongsTo("App\TimeUnit")->withDefault();
    }

    public function eventType(): BelongsTo
    {

        // An event has an event type
        return $this->belongsTo("App\EventType", "event_type_id");
    }

    public function medicationsPatients() {
        return $this->belongsToMany('App\MedicationPatient', 'medication_patient_event',
            'event_id', 'medication_patient_id');
    }

    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo("App/Questionnaire");
    }

    public static function tasks() {
        return Event::where('event_type_id', '1')->orWhere('event_type_id', '3')->get();
    }

    public static function getTasksToBeReminded($currentDateTime, $minutesNotifyAdvance) {


        $startDateTimeLimit = $currentDateTime->clone()->startOfDay();

        //This is the end limit of the period that cannot be exceeded, because we don't want to get tasks back
        //from the next day
        $absoluteEndDateTimeLimit = $currentDateTime->clone()->endOfDay();
        //We want to notify the user a certain amount of minutes in advance
        $advanceEndDateTimeLimit = $currentDateTime->clone()->addMinutes($minutesNotifyAdvance);
        //We check if the limit we just created is still on the same day
        $endDateTimeLimit = $advanceEndDateTimeLimit <= $absoluteEndDateTimeLimit ? $advanceEndDateTimeLimit : $absoluteEndDateTimeLimit;


        $taskEventID = EventType::where('name', 'task')->first()->id;

        $tasks = Event::
            //check if event is a task
            where('event_type_id', $taskEventID)
            //check if the task takes place in period, or if a repetition of the task could potentially take place in period
            ->where(function($query) use ($endDateTimeLimit, $startDateTimeLimit) {
                //normal task
                $query->whereBetween('start_date', [$startDateTimeLimit, $endDateTimeLimit])
                //repeating task
                ->orWhere(function($query) use ($endDateTimeLimit, $startDateTimeLimit) {
                    //check if task is repeating
                   $query->whereNotNull('time_unit_id')
                   //check if task didn't stop before period
                   ->where(function($query) use ($startDateTimeLimit) {
                       $query->where('end_date', '>=', $startDateTimeLimit)
                           ->orWhereNull('end_date');
                   })
                   //check if task starts before the end of period
                   ->where('start_date', '<=', $endDateTimeLimit);
                });
            })
            //task only has to be returned if it wasn't confirmed
            ->where('confirmed', false)
            //task only has to be returned if there are still reminders that have to be sent
            ->where(function($query) {
                $query->where('reminder_sent_before', false)
                    ->orwhere('reminder_sent_at_time', false)
                    ->orwhere('reminder_sent_after', false);
            })
            ->get();

        return $tasks;
    }

    public static function getRepeatingTasks() {
        $taskEventID = EventType::where('name', 'task')->first()->id;

        $repeatingTasks = Event::where('event_type_id', $taskEventID)
            ->whereNotNull('time_unit_id')->get();

        return $repeatingTasks;
    }

    public function excludedDates() {
        return $this->hasMany('App\ExcludedDate');
    }

    public function isEventFromUserId(int $id): bool
    {
        return $this->patient_id == $id;
    }

    public function patient(): BelongsTo {
        return $this->belongsTo("App\User");
    }
}
