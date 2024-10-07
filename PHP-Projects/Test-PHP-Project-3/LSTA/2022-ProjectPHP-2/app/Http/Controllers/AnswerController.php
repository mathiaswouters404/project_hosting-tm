<?php

namespace App\Http\Controllers;

use App\Answer;
use Carbon\Carbon;
use Json;
use App\Questionnaire;
use App\Services\AnswerService;
use Illuminate\Http\Request;

class AnswerController extends Controller
{


    private $answerService;

    public function __construct(AnswerService $answerService)
    {
        $this->answerService = $answerService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {
        $num1 =Questionnaire::getAnswerCountQuestionnaire(2, now());
        $num2 = Questionnaire::getQuestionCountQuestionnaire(2);
        $date = Carbon::now()->format('Y-m-d');
        $num3 =Questionnaire::getProgressQuestionnaire(2, now());
        return compact('num1','num2','date','num3');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Answer $answer)
    {
        $this->authorize('view',$answer);
        return response()->json($answer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        //
    }
}
