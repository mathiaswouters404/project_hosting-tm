<?php

namespace App;

use Auth;
use DB;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'admin', 'status', 'firstName', 'lastName', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function patientLogs() {
        return $this->hasMany('App\Log', 'patient_id');
    }

    public function creatorLogs() {
        return $this->hasMany('App\Log', 'creator_id');
    }

    public function prescriptions() {
        return $this->hasMany('App\MedicationPatient', 'patient_id');
    }

    public function events() {
        return $this->hasMany('App\Event', 'patient_id');
    }

    public function drafterQuestionnaires() {
        return $this->hasMany('App\Questionnaire', 'drafter_id');
    }

    public function patientQuestionnaires() {
        return $this->hasMany('App\Questionnaire', 'patient_id');
    }

    public function patientRights() {
        return $this
            ->hasMany('App\PatientRight', 'patient_id')
            ->join('right_types', 'patient_rights.right_type_id', '=', 'right_types.id');
    }

    public function hasRight(string $rightName) {
        return $this->patientRights()->where("name", "=", $rightName)->first()['has_right'] == 1;
    }

    public function getPatientRightsNames() {
        $patientRights = $this->patientRights;
        $patientRightsNames = [];

        foreach($patientRights as $patientRight) {
            if($patientRight->has_right) {
                array_push($patientRightsNames, $patientRight->name);
            }
        }

        return $patientRightsNames;
    }

    public function personnel() {
//        return $this->hasMany('App\PatientCarePersonnel', 'personnel_id');
        return $this->belongsToMany('App\User', 'patient_care_personnels',
            'patient_id', 'personnel_id');
    }

    public function patients() {
//        return $this->hasMany('App\PatientCarePersonnel', 'patient_id');
        return $this->belongsToMany('App\User', 'patient_care_personnels',
            'personnel_id', 'patient_id');
    }

    public function role() {
        return $this->belongsTo('App\Role')->withDefault();
    }

    public function isPatientFromUser(int $patientId) {
        $count = PatientCarePersonnel::where([
            ["personnel_id", "=", $this->id],
            ["patient_id", "=", $patientId]
        ])->get()->count();

        return $count >= 1;
    }

    public function careTakerEmails() {
        $caretakerID = Role::where('name', 'Mantelzorger')->first()->id;

        $emailsCaretakerPersonnel = PatientCarePersonnel::join('users', 'patient_care_personnels.personnel_id', '=', 'users.id')
            ->where('role_id', $caretakerID)
            ->where('patient_id', $this->id)
            ->select('email')
            ->get();

        return $emailsCaretakerPersonnel;
    }
}
