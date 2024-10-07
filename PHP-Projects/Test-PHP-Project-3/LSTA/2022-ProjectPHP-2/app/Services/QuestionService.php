<?php

namespace App\Services;

use App\Question;
use App\Questionnaire;
use Illuminate\Http\Request;

class QuestionService{

public function bulkCreate(Request $request,Questionnaire $questionnaire): array
{

    $questions = [];
    foreach( $request->questions as $question){

        $question2 = [
            'description' => $question,
            'questionnaire_id' => $questionnaire->id
        ];

        array_push($questions, $question2);
    }

    Question::insert($questions);

    return $questions;
}

public function getUnansweredQuestions($id, $date){
    $questionnaire = Questionnaire::select("id","name")->where("id","=",$id)->get()->first();
    $questions = Questionnaire::getUnansweredQuestions($id, $date);
    return compact('questionnaire','questions');
}


}
