<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicationPatient extends Model
{
    protected $fillable = ['selfPrescribed', 'patient_id', 'medication_id', 'dosage', 'reason', 'startDate', 'endDate'];

    public function medication() {
        return $this->belongsTo('App\Medication')->withDefault(); //MedicationPatient has 1 Medication
    }

    public function patient() {
        return $this->belongsTo('App\User', 'patient_id')->withDefault(); //MedicationPatient has 1 Patient
    }

    public function events() {
        return $this->belongsToMany('App\Event', 'medication_patient_event',
            'medication_patient_id', 'event_id');
    }
}
