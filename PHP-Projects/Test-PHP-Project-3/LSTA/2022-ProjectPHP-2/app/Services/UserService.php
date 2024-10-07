<?php

namespace App\Services;

use App\PatientRight;
use App\RightType;
use Auth;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use \Illuminate\Support\Collection;

class UserService{
    public function getPatients(): Collection
    {
        return Auth::user()->patients;
    }
}
