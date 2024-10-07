<?php

namespace App;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        "title",
        "description",
        "date",
        "patient_id",
        "creator_id",
        "event_id",
        "visitor"
        ];

    public function patient(){
        // A log belongs to a patient (user).
        return $this->belongsTo("App\User", "patient_id");
    }

    public function creator(){
        // A log belongs to a creator (user).
        return $this->belongsTo("App\User", "creator_id");
    }

    public function event(){
        // A log belongs to an event.
        return $this->belongsTo("App\Event", "event_id");
    }
    public static function getEventsOfCurrentUser($patientsIds){
        return Event::orderBy('start_date')
            ->orderBy('created_at')
            ->orderBy('name')
            ->wherein("patient_id", $patientsIds)
            ->get();
    }

    public static function filteredLogsBySearch($patientsIds, $search){
        if(Auth::user()->role->name == "Dokter"){
            return self::getMedicationLogs($patientsIds, $search);
        }
        return self::formatLogsBySearch($patientsIds, $search);
    }


    private static function getLogsBySearch($patientsIds, $search){
        return Log::orderBy('Date', 'desc')->orderBy('created_at', 'desc')->orderBy('title')
            // join user table.
            ->with("patient")
            // Join event table.
            ->with("event")

            // get only the patients from the logged in user.
            ->wherein("patient_id", $patientsIds)
            // get all id's that return from the query.
            ->wherein("id", function($subQuery) use($search) {
                // Selects id's from logs corresponding to title or description.
                $subQuery->select("logs.id")->from("logs")
                    ->orwhere('logs.title', 'like', $search)
                    ->orwhere('logs.description', 'like', $search)
                    // and results that correspond to the patient first and last name.
                    ->join("users", "users.id", "=", "logs.patient_id")
                    // Results for both first and last name together (both ways).
                    ->orwhere(DB::raw("CONCAT(users.firstName, ' ', users.lastName)"), 'like', $search)
                    ->orwhere(DB::raw("CONCAT(users.lastName, ' ', users.firstName)"), 'like', $search)
                    ->orwhere(DB::raw("CONCAT(users.firstName, users.lastName)"), 'like', $search)
                    ->orwhere(DB::raw("CONCAT(users.lastName, users.firstName)"), 'like', $search);
            });
    }
    private static function formatLogsBySearch($patientsIds, $search){
        return self::getLogsBySearch($patientsIds, $search)
            ->get()
            ->transform(function ($item, $key){
                $item->patient->fullName = $item->patient->firstName . " " . $item->patient->lastName;
                return $item;
            });
    }

     private static function getMedicationLogs($patientsIds, $search){
       return  self::getLogsBySearch($patientsIds, $search)
           ->whereHas('event', function ($query) {
            $query->with("event_type")->where("name","like","medication");
        })->get();
}
}
