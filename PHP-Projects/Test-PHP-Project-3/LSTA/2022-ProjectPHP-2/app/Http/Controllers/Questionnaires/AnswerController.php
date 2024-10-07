<?php

namespace App\Http\Controllers\Questionnaires;

use App\Answer;
use App\Http\Controllers\Controller;
use App\Question;
use App\Services\AnswerService;
use App\Services\QuestionService;
use Illuminate\Http\Request;
use Json;

class AnswerController extends Controller
{


    private $answerService;

    public function __construct(AnswerService $answerService)
    {
        $this->answerService = $answerService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $this->authorize('create',Answer::class);
        $question = Question::findById($request->question_id);
        $this->authorize('answer',$question);

        $rules = [
            'question_id'=>'required',
            'answer' => 'required',
            'date' => 'required'

        ];

        $customMessages = [
            'answer.required' => 'You can t leave the question open'
        ];

        $this->validate($request, $rules, $customMessages);

        $this->answerService->create($request);
        return Json::createJsonResponse("success","question successfully answered");

    }

}
