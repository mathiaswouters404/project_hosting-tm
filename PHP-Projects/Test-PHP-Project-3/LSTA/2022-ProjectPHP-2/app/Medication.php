<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = ['name', 'description'];

    public function medicationPatients() {
        return $this->hasMany('App\MedicationPatient'); // Medication has many MedicationPatients
    }
}
