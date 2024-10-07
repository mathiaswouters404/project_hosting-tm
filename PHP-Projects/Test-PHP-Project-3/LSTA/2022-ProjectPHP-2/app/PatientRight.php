<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientRight extends Model
{
    protected $fillable = array('patient_id', 'right_type_id', 'has_right');

    public function user()
    {
        return $this->belongsTo('App\User')->withDefault();   // a record belongs to a genre
    }

    public function rightType()
    {
        return $this->belongsTo('App\RightType')->withDefault();   // a record belongs to a genre
    }
}
