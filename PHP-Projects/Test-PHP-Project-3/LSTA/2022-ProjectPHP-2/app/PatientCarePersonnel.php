<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientCarePersonnel extends Model
{
    public function personnel() {
        return $this->belongsTo('App\User', 'personnel_id')->withDefault();
    }

    public function patient() {
        return $this->belongsTo('App\User', 'patient_id')->withDefault();
    }
}
