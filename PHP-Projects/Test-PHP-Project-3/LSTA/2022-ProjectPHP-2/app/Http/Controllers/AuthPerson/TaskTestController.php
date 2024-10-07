<?php

namespace App\Http\Controllers\AuthPerson;

use App\Event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Json;

class TaskTestController extends Controller
{
    public function index() {
        return view('auth.authPerson.tasks.index');
    }
}
