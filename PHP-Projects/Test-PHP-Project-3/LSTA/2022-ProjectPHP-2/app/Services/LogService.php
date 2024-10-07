<?php

namespace App\Services;

use App\Event;
use App\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function React\Promise\all;
use function Sodium\add;

class LogService{
    // service is logica
    public function createLog(Request $request){
        $log = new Log($request->all());
        $log->creator_id = Auth::user()->id;
        $log->save();
    }

    public function updateLog($log, $request){
        $log->title = $request->title;
        $log->description = $request->description;
        $log->date = $request->date;
        $log->visitor = $request->visitor;
        $log->save();
    }

    public function getPatientUserIds(){
        $patientsIds = Auth::user()->patients->pluck("id")->toArray();
        array_push($patientsIds, Auth::user()->id);
        return $patientsIds;
    }

    public function getFilteredLogsOfCurrentUser($search){
        return Log::filteredLogsBySearch($this->getPatientUserIds(), $search);
    }

    public function getEventsOfCurrentUser(){
        return Log::getEventsOfCurrentUser($this->getPatientUserIds());
    }


}
