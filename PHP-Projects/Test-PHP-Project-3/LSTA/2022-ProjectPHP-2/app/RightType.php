<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RightType extends Model
{
    public function patientRights()
    {
        return $this->hasMany("App/PatientRight");
    }

    public static function allRightsWithDisplayName() {
        return RightType::get()
            ->transform(function ($item) {
                $item->display_name = ucfirst(str_replace('_', ' ', $item->dutch_name));
                return $item;
            })
            ->makeHidden(['created_at', 'updated_at']);
    }
}
