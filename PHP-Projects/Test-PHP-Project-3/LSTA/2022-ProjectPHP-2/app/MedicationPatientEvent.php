<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationPatientEvent extends Model
{
    public function medicationPatient(): BelongsTo
    {

        // A medicationPatientEvent belongs to one medicationPatient
        return $this->belongsTo("App/medicationPatient", "medication_patient_id");
    }

    public function event(): BelongsTo
    {

        // A medicationPatientEvent belongs to one event
        return $this->belongsTo("App/Event", "event_id");
    }
}
