<?php

namespace App\Http\Controllers\Questionnaires;

use App\Http\Controllers\Controller;
use App\Question;
use App\Questionnaire;
use App\Services\QuestionnaireService;
use App\Services\QuestionService;
use Cassandra\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Json;
use Response;
use Validator;

class QuestionController extends Controller
{

    private $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id, Request $request)
    {

        $questionnaire = Questionnaire::findById($id);
        $this->authorize('view',$questionnaire);

        return $this->questionService->getUnansweredQuestions($id, $request->date);
    }

}
