<?php

namespace App\Services;

use App\Answer;
use Illuminate\Http\Request;

class AnswerService{

    public function create(Request $request){
       $answer = new Answer($request->all());
       $answer->date = $request->date;
       $answer->save();
    }
}
