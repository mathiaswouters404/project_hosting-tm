<?php

namespace App\Services;

use App\Answer;
use App\Question;
use App\Questionnaire;

use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use phpDocumentor\Reflection\Utils;


class QuestionnaireService{

    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function createQuestionnaire(Request $request){

        $questionnaire = new Questionnaire($request->all());
        $questionnaire->creator_id = Auth::user()->id;

        $questionnaire->save();

        return $questionnaire;
    }

    public function create(Request $request){
        $questionnaire = $this->createQuestionnaire($request);
        $this->eventService->createQuestionnaireEvent($questionnaire, $request);
        return $questionnaire;
    }

    public function getQuestionnaires($user){
        return  Questionnaire::getQuestionnairesPatient($user);
    }


    public function getProgressQuesionnaire(int $id,Request $request){
        return Questionnaire::getProgressQuestionnaireObject($id, $request->date);
    }



    public function getQuestionsWithAnswersByQuestionnaireId($id){
        return Questionnaire::getQuestionsWithAnswersByQuestionnaireId($id);
    }

    public function getAnswersWithQuestionByQuestionnaireId($id){
        return Questionnaire::getAnswersWithQuestionByQuestionnaireId($id);
    }

    public function sortAnswers($id){
        $answers = $this->getAnswersWithQuestionByQuestionnaireId($id);
        $answerMap = [];
        foreach($answers as $answer ){
            $key = $answer->date;
            $map = [];
            if(key_exists($key,  $answerMap)){
                $map = $answerMap[$key];
            } else{
                $map = [];
            }
            array_push($map, $answer);
            $answerMap[$key] = $map;
        }
        return $answerMap;
    }
}
