<?php

namespace App\Http\Controllers\RolesMiddle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Json;

class TestController extends Controller
{
    public function index() {
        $patients = auth()->user()->patients;
        $result = compact('patients');
        Json::dump($result);
    }
}
